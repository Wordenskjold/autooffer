<?php

use Doctrine\ORM\EntityRepository;

require_once('DoctrineDataStore.php');

/**
 * DoctrineDataStore is a wrapper class, encapsulating logic on how to 
 * save and load entities in the application. Uses Doctrine as ORM and 
 * memcached as in-memory store.
 *
 * TODO: Add exceptions, e.g. when commit() fails.
 *
 * @author Frederik Wordenskjold
 * @version 1.0
 */
class DoctrineCacheDataStore extends DoctrineDataStore{

	const DEFAULT_TTL = 86400; // 24 hours

	protected $cache;
	private   $transactionCache;

	function __construct($config){
		parent::__construct($config);
		$this->attachCache($config['cache']);
		$this->transactionCache = array();
	}

	function find($type, $id){
		$_type = parent::getProperTypeName($type)->name;
		$key = $_type . '_' . $id;
		if(!$res = $this->cache->fetch($key)){
			$res = parent::find($type, $id);

			/* We never expire the cache because of cache stampede. http://en.wikipedia.org/wiki/Cache_stampede
			 * We should, however, create a scheduler to handle cache cleanup.
			 * https://github.com/Wordenskjold/autooffer/issues/10
			 * 
			 * NOTE: We ARE expiring the cache now, so it doesn't grow out of control.
			 */
			$this->cache->save($key, $res, self::DEFAULT_TTL);
		}
		if($this->orm->getUnitOfWork()->getEntityState($res) !== Doctrine\ORM\UnitOfWork::STATE_MANAGED){
			$res = $this->orm->merge($res);
		}
		return $res;
	}

	function save($object){
		parent::save($object);
		$this->transactionCache[] = array('obj' => $object, 'ttl' => self::DEFAULT_TTL);
	}

	/* TODO: Wrap return in some kind of message object */
	function commit(){
		if(!parent::commit()){
			$this->rollBack();
			return false;
		}
		$this->commitCacheTransaction();
		return true;
	}

	protected function commitCacheTransaction(){
		foreach($this->transactionCache as $cacheEntry){
			list($object, $ttl) = array_values($cacheEntry);
			$key = $this->generateCacheKey($object);
			$this->cache->save($key, $object, $ttl);
		}
		empty($this->transactionCache);
	}

	protected function rollBack(){
		empty($this->transactionCache);
	}

	function generateCacheKey($object){
		$id = $object->getId();
		$type = get_class($object);
		return $type . "_" . $id;
	}

	private function attachCache($cacheDriver){
		if(!$cacheDriver ||
			$cacheDriver !== 'memcached' ||
			!$this->CI->config->load($cacheDriver, true, true)){
			log_message("error", "The provided cache driver '" . $cacheDriver . "' is not supported");
			return false;
		}
		$memcached = new Memcached();
		foreach($this->CI->config->item($cacheDriver)[$cacheDriver] as $key => $server){
			$memcached->addServer($server['hostname'], $server['port']);
		}
		$this->cache = new \Doctrine\Common\Cache\MemcachedCache();
		$this->cache->setMemcached($memcached);
		$config = $this->orm->getConfiguration();
		$config->setQueryCacheImpl($this->cache);
		$config->setResultCacheImpl($this->cache);
		$config->setMetadataCacheImpl($this->cache);
	}
}
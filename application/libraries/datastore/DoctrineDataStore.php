<?php

require_once('DataStore.php');

/**
 * @author Frederik Wordenskjold
 * @version 1.0
 */
class DoctrineDataStore implements DataStore{

	protected $CI;
	protected $orm;
	protected $namespace;

	function __construct($config){
		$this->CI = &get_instance();
		$this->namespace = $config['namespace'];
		$this->loadORM();
	}

	function findWithCondition(){}

	function getRepo($type){
		return $this->orm->getRepository($this->namespace .'\\' . $type);
	}

	function find($type, $id){
		return $this->orm->find($this->namespace .'\\' . $type, $id);
	}

	function findAll($type, $limit = false){
		return $this->orm->findAll($this->namespace .'\\' . $type);
	}

	function save($object){
		$this->orm->persist($object);
	}

	function saveAll(array $objects){
		foreach($objects as $object){
			$this->save($object);
		}
		return $this->commit();
	}

	function remove($object){
		$this->orm->remove($object);
	}

	function update($object){
		$this->save($object);
	}

	function commit(){
		$ok = false;
		try{
			$this->orm->flush();
			$ok = true;
		} catch(Exception $e){
			log_message('error', $e->getMessage());
		}
		return $ok;
	}

	function getProperTypeName($type){
		return $this->orm->getMetadataFactory()->getMetaDataFor($this->namespace .'\\' . $type);
	}

	function loadORM(){
		include APPPATH . 'config/database.php';
		$metaData = array(APPPATH);
		$modelPath = APPPATH;
		$proxyDir = APPPATH . $this->namespace . '/proxies';
		$isDevMode = true;
		$dbParams = array(
		    'driver'   => $db[$active_group]['dbdriver'],
		    'host'     => $db[$active_group]['hostname'],
		    'user'     => $db[$active_group]['username'],
		    'password' => $db[$active_group]['password'],
		    'dbname'   => $db[$active_group]['database'],
		);
		$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($metaData, $isDevMode, $proxyDir);
		$entitiesClassLoader = new Doctrine\Common\ClassLoader($this->namespace, rtrim(APPPATH, "/" ));
        $entitiesClassLoader->register();
		$this->orm = Doctrine\ORM\EntityManager::create($dbParams, $config);
	}
}
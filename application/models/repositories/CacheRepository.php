<?php

namespace models\repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;

class CacheRepository extends EntityRepository{
	function __construct($em, $meta){
		parent::__construct($em, $meta);
        $cache       = $em->getConfiguration()->getQueryCacheImpl();
        $this->cache = $cache ? $cache : new ArrayCache();
	}
}
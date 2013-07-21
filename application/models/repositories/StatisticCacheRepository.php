<?php

namespace models\repositories;

class StatisticCacheRepository extends CacheRepository{

	function get($key, $execute = true){
		$query = $this->_em->createQuery("SELECT stat FROM models\\Statistic stat WHERE stat.key = '$key'");
		return $execute ? $query->getResult() : $query;
	}

	function getSingle($key){
		$res = $this->get($key, false);
		return $res->getOneOrNullResult();
	}

	function num($key, $value = false){
		$query = "SELECT COUNT(stat.key) FROM models\\Statistic stat WHERE stat.key = '$key'";
		if($value){
			$query.= " AND stat.value = '$value'";
		}
		return $this->_em->createQuery($query)->getSingleScalarResult();
	}
}
<?php

namespace models\repositories;

class UserCacheRepository extends CacheRepository{
	
	function find($string){
		return $this->query("SELECT u FROM models\\User u WHERE u.email = '$string'", true);
	}

	function getUnverifiedUser($hash){
		return $this->query("SELECT u FROM models\\User u WHERE u.verificationHash = '$hash'", true);
	}

	private function query($query, $single = false){
		$res = $this->_em->createQuery($query)->getResult();
		if(!empty($res) && $single){
			$res = array_pop($res);
		}
		return $res;
	}
}
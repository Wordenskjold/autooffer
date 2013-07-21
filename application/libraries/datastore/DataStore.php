<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface DataStore {
	
	function find($type, $id);

	function findAll($type, $limit = false);

	function findWithCondition();

	function save($object);

	function saveAll(array $objects);

	function update($object);

	function remove($object);

	function commit();

	function getRepo($type);
}

/* End of file  */
/* Location: ./application/controllers/ */
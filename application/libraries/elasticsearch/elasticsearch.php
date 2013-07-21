<?php 
use Sherlock\Sherlock;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Elasticsearch Sherlock wrapper.
 *
 * @author Frederik Wordenskjold
 * @version 1.0
 */

class Elasticsearch {

	private $sherlock;
	private $config;
	
	public function __construct($config){
		$this->CI     =& get_instance();
		$this->config = $config;
		$this->init();
	}

	/**
	 * Simple method that looks through all types, and all values
	 * to find $value in $index.
	 * @return The source of the result array, containing the actual data of the matched rows.
	 */
	public function find($index, $value){
		$request = $this->sherlock->search();

		$query = Sherlock::queryBuilder()->QueryString()->query($value);
		$result = $request->index($index)->query($query)->execute();
		return ipull($result->hits, 'source');
	}

	public function getHandler(){
		return $this->sherlock;
	}

	protected function init(){
		$this->sherlock = new Sherlock();
		foreach($this->config['nodes'] as $node){
			$this->sherlock->addNode($node->endpoint, $node->port);
		}
	}

}
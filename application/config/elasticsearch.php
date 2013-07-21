<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['handler'] = 'sherlock';
$config['nodes'] = array(
	(object)array('endpoint' => 'localhost', 'port' => '9200')
);
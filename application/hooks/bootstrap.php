<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function bootstrap(){

	$CI = &get_instance();

	/* Composer autoload file */
	require_once(APPPATH . '/libraries/vendor/autoload.php');

	/* Load some kind of datastore controller */
	$CI->load->library('datastore/DoctrineCacheDataStore', '', 'dataStore');

	/* Load objects which should not be attached to the global $CI object
	 * Usually static classes and singletons */ 
	$CI->load->object('response');
	$CI->load->object('utils');
	$CI->load->object('SecurityUtils');

	/* Load external sparks */
	#$CI->load->spark('codeigniter-restclient');

	/* Change delimeters of parser, to match Mustache template engine */
	
	#DO WE WANT TO MATCH IT?
	if(isset($CI->parser)){
		$CI->parser->set_delimiters('{{', '}}');
	}
	
	$CI->load->helper('language');
}
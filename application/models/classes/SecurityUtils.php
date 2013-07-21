<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SecurityUtils{

	/* Static class */
	private function __construct(){ }

	static function generateRandomToken(){
		return md5(mt_rand());
	}
}
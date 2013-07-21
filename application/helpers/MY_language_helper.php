<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('l'))
{
	function l($line)
	{
		$CI =& get_instance();
		$line = $CI->lang->line($line);
		return $line;
	}

	function t($line){
		return l($line);
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['base'] = '/static/';

/* Required css files */
$config['css'] = array(
	$config['base'] . 'css/reset.css',
	$config['base'] . 'js/libs/bootstrap/css/bootstrap.css',
	$config['base'] . 'css/foundation.min.css',
	$config['base'] . 'fonts/fontawesome/font-awesome.min.css',
					  '//fonts.googleapis.com/css?family=Open+Sans:400,600,300,700',
	$config['base'] . 'css/main.css'
);

/* Required javascript files. All other js files
   are loaded with require.js, through app.js */
$config['js'] = array(
	$config['base'] . 'js/libs/require/require.min.js',
	$config['base'] . 'js/app.js'
);
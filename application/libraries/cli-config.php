<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

define('BASEPATH', '/Users/wordenskjold/Sites/autooffer/');
define('APPPATH', BASEPATH . 'application/');
include APPPATH . 'config/database.php';

require_once(APPPATH . 'models/Entity.php');

$paths = array(APPPATH . 'models');
$isDevMode = false;
$dbParams = array(
    'driver'   => $db[$active_group]['dbdriver'],
    'host'     => $db[$active_group]['hostname'],
    'user'     => $db[$active_group]['username'],
    'password' => $db[$active_group]['password'],
    'dbname'   => $db[$active_group]['database'],
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$em = EntityManager::create($dbParams, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
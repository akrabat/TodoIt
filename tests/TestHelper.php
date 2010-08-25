<?php
// Based on http://weierophinney.net/matthew/archives/190-Setting-up-your-Zend_Test-test-suites.html


error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/London');

define('APPLICATION_ENV', 'unittesting');
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('APPLICATION_CONFIG', APPLICATION_PATH . '/configs/application.ini');

// Directories for include path
$root        = realpath(dirname(__FILE__) . '/../');
$library     = $root . '/library';
$tests       = $root . '/tests';
$models      = $root . '/application/models';
$controllers = $root . '/application/controllers';

$path = array(
    $models,
    $library,
    $tests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));


require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


Zend_Registry::set('testRoot', $root);
Zend_Registry::set('testBootstrap', $root . '/application/Bootstrap.php');


// Unset global variables
unset($root, $library, $models, $controllers, $tests, $path);
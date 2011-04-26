<?php
// Based on http://weierophinney.net/matthew/archives/190-Setting-up-your-Zend_Test-test-suites.html

// PHP settings
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/London');

define('APPLICATION_ENV', 'unittesting');
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Directories for include path
$root = realpath(dirname(__FILE__) . '/../');
$library = $root . '/library';
$models = $root . '/application/models';

$path = array(
    $library,
    $models,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

// Unset global variables
unset($root, $library, $models, $path);
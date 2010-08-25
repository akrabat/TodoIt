<?php

// load ACL object
require_once APPLICATION_PATH.'/Acl.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
//    function _initCache()
//    {
//      $frontendOptions = array('lifetime' => '7200',
//        'automatic_serialization'=>true);
//      $backendOptions = array(
//        'cache_dir' => APPLICATION_PATH . '/../var/cache');
//      $cache = Zend_Cache::factory('Core', 'File', 
//          $frontendOptions, $backendOptions);
//      return $cache;
//    }
}


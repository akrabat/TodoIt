<?php

class Application_Service_TaskService
{
    /**
     * @var Application_Acl
     */
    protected $_acl;

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;
    
    /**
     * @var Application_Model_User
     */
    protected $_currentUser;

    /**
     * @return Application_Acl
     */
    public function getAcl()
    {
        if (!$this->_acl) {
            $this->_acl = new Application_Acl();
            $this->_acl->allow('user', 'task', array('create', 'read', 'update'));
            $this->_acl->allow('administrator', 'task', array('delete'));
        }
        
        return $this->_acl;
    }
    
    /**
     * @return Application_Model_User
     */    
    public function getCurrentUser()
    {
        if (!$this->_currentUser) {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $this->_currentUser = $auth->getIdentity();
            } else {
                $this->_currentUser = new Application_Model_User();
            }
        }
        return $this->_currentUser;
    }
    
    public function fetchOutstanding()
    {
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, 'task', 'read')) {
            throw new Exception("Not Allowed");
        }        
        
        $cacheId = 'outstandingTasks';
        $cache = $this->_getCache();
        $tasks = $cache->load($cacheId);
        if ($tasks === false) {
            $mapper = new Application_Model_TaskMapper();
            $tasks = $mapper->fetchOutstanding();
            $cache->save($tasks, $cacheId, array('tasks'));
        }
        return $tasks;
    }
    
    public function fetchRecentlyCompleted()
    {
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, 'task', 'read')) {
            throw new Exception("Not Allowed");
        }        
        
        $cacheId = 'recentlyCompletedTasks';
        $cache = $this->_getCache();
        $tasks = $cache->load($cacheId);
        if ($tasks === false) {
            $mapper = new Application_Model_TaskMapper();
            $tasks = $mapper->fetchRecentlyCompleted();

            $cache->save($tasks, $cacheId, array('tasks'));
        }
        return $tasks;
    }
    
    public function loadById($id)
    {
        $id = (int)$id;
        if($id <= 0) {
            return false;
        }
        
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, 'task', 'read')) {
            throw new Exception("Not Allowed");
        }
        
        $cacheId = 'task'.$id;
        $cache = $this->_getCache();
        $task = $cache->load($cacheId);
        if ($task === false) {
            $mapper = new Application_Model_TaskMapper();
            $task = $mapper->loadById($id);
            if(!$task) {
                return false;
            }
            
            $cache->save($task, $cacheId, array('tasks'));
        }
        return $task;
    }
    
    public function create(array $data)
    {
        $task = new Application_Model_Task($data);
        
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, $task, 'create')) {
            throw new Exception("Not Allowed");
        }
        
        $mapper = new Application_Model_TaskMapper();
        $mapper->addTask($task);
        $this->_cleanCache();
        return $task;
    }
    
    public function update($data)
    {
        if (is_array($data)) {
            $task = new Application_Model_Task($data);
        } else if ($data instanceof Application_Model_Task) {
            $task = $data;
        }
        /* @var $task Application_Model_Task */
        
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, $task, 'create')) {
            throw new Exception("Not Allowed");
        }
        
        $mapper = new Application_Model_TaskMapper();
        $mapper->updateTask($task);
        $this->_cleanCache();
        return $task;
    }

    
    /**
     * @return Zend_Cache_Core
     */
    protected function _getCache()
    {
        if (!$this->_cache) {
            $fc = Zend_Controller_Front::getInstance();
            $cacheManager = $fc->getParam('bootstrap')
                ->getResource('cachemanager');
            $cache = $cacheManager->getCache('default');
            $this->_cache = $cache;
        }
        return $this->_cache;
    }
    
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
    }    

    protected function _cleanCache()
    {
        $this->_getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array('tasks'));
    }    
}
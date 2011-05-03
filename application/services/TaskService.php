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
            $this->_acl->allow('user', 'task',
                    array('read', 'update', 'delete'),
                    new Application_Model_Acl_AssertUserOwnsTask());
            $this->_acl->allow('user', 'task', 'create');
            $this->_acl->allow('administrator', 'task',
                    array('read', 'update', 'delete'));
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

    public function fetchOutstanding($page, $numberPerPage=5)
    {
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        $cacheId = 'outstandingTasks';
        $cache = $this->_getCache();
        $tasks = $cache->load($cacheId);
        if ($tasks === false) {
            $mapper = new Application_Model_TaskMapper($acl);
            $tasks = $mapper->fetchOutstanding();
            $tasks->getAdapter()->setAcl($acl);
            $tasks->getAdapter()->setUser($user);
            $cache->save($tasks, $cacheId, array('tasks'));
        }
        $tasks->setCurrentPageNumber($page);
        $tasks->setItemCountPerPage($numberPerPage);        
        return $tasks;
    }

    public function fetchRecentlyCompleted()
    {
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        $cacheId = 'recentlyCompletedTasks';
        $cache = $this->_getCache();
        $tasks = $cache->load($cacheId);
        if ($tasks === false) {
            $mapper = new Application_Model_TaskMapper();
            $tasks = $mapper->fetchRecentlyCompleted();
            foreach ($tasks as $i => $task) {
                if (!$acl->isAllowed($user, $task, 'read')) {
                    unset($task[$i]);
                }
            }
            $cache->save($tasks, $cacheId, array('tasks'));
        }
        return $tasks;
    }

    public function loadById($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }
        $cacheId = 'task' . $id;
        $cache = $this->_getCache();
        $task = $cache->load($cacheId);
        if ($task === false) {
            $mapper = new Application_Model_TaskMapper();
            $task = $mapper->loadById($id);
            if (!$task) {
                return false;
            }
            $acl = $this->getAcl();
            $user = $this->getCurrentUser();
            if (!$acl->isAllowed($user, $task, 'read')) {
                throw new Exception("Not Allowed");
            }
            $cache->save($task, $cacheId, array('tasks'));
        }
        return $task;
    }

    public function processThroughInputFilter($data)
    {
        $filters = array('*' => array(new Zend_Filter_StripTags()));
        $validators = array('*' => array());
        $input = new Zend_Filter_Input($filters, $validators, $data);
        $input->setDefaultEscapeFilter(new Zend_Filter_StringTrim());
        $input->process();
        if ($input->hasInvalid()) {
            return false;
        }

        foreach ($data as $key => $value) {
            $data[$key] = $input->$key;
        }

        return $data;
    }

    public function create(array $data)
    {
        $data = $this->processThroughInputFilter($data);
        if (!$data) {
            throw new Exception('Invalid data');
        }
        $task = new Application_Model_Task($data);
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, $task, 'create')) {
            throw new Exception("Not Allowed");
        }

        $mapper = new Application_Model_TaskMapper();
        $mapper->save($task);
        $this->_cleanCache();
        return $task;
    }

    public function update($data)
    {
        if (is_array($data)) {
            $data = $this->processThroughInputFilter($data);
            if (!$data) {
                throw new Exception('Invalid data');
            }
            $task = new Application_Model_Task($data);
        } elseif ($data instanceof Application_Model_Task) {
            $task = $data;
        }
        /* @var $task Application_Model_Task */
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        if (!$acl->isAllowed($user, $task, 'create')) {
            throw new Exception("Not Allowed");
        }
        $mapper = new Application_Model_TaskMapper();
        $mapper->save($task);
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
        $this->_getCache()->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array('tasks'));
    }

}
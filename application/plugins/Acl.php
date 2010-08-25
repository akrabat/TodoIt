<?php

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Application_Acl
     */
    protected $_acl;
    
    /**
     * @var Application_Model_User
     */
    protected $_currentUser;    
    
    /**
     * Get ACL lists
     * 
     * @return Application_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $acl = new Application_Acl();
    
            // Rules for controller access
            $acl->deny();
            $acl->allow('guest', 'authController', null);
            $acl->allow('guest', 'errorController', null);
            $acl->allow('user', 'indexController', null);
            
            $this->_acl = $acl;
        }
        return $this->_acl;
    }
    
    /**
     * @return Zend_Auth
     */
    function getAuth()
    {
        return Zend_Auth::getInstance();
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
    
    /**
     * Dispatch loop startup plugin: get identity and acls
     * 
     * @param Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    { 
        $acl = $this->getAcl();
        $user = $this->getCurrentUser();
        $resource = $request->getControllerName() . 'Controller';
        $privilege = $request->getActionName();
        
        $allowed = $acl->isAllowed($user, $resource);
        if (!$allowed) {
            $controller = 'auth';
            $auth = $this->getAuth();
            if (!$auth->hasIdentity()) {
                $action = 'index';
            } else {
                $action = 'permissions';
            }

            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoSimple($action, $controller);
        }
    }

}


<?php

class Application_Model_Acl_AssertUserOwnsTask implements Zend_Acl_Assert_Interface
{
    public function assert(Zend_Acl $acl, 
                           Zend_Acl_Role_Interface $role = null, 
                           Zend_Acl_Resource_Interface $resource = null, 
                           $privilege = null)
    {   
        if (!$resource instanceof Application_Model_Task) {
            throw new Exception('Not a task');
        }

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            return false;
        }
        $user = $auth->getIdentity();
        
        // Then do the check
        /* @var Application_Model_Task $resource */
        return $resource->getCreatedBy() == $user->getId();
    }
}
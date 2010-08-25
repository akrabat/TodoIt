<?php

class Application_Acl extends Zend_Acl
{
    public function __construct()
    {
        // Roles
        $this->addRole('guest');
        $this->addRole('user', 'guest');
        $this->addRole('administrator', 'user');

        // Resources (Controllers)
        $this->addResource(new Zend_Acl_Resource('indexController'));
        $this->addResource(new Zend_Acl_Resource('authController'));
        $this->addResource(new Zend_Acl_Resource('errorController'));
        
        // Resources (Models)
        $this->addResource(new Zend_Acl_Resource('task'));
    }
}
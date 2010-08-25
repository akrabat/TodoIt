<?php

class Application_Model_User implements Zend_Acl_Role_Interface, Zend_Acl_Resource_Interface
{
    protected $_username = 'guest';
    protected $_password;
    protected $_role = 'guest';
    protected $_date_created;
    
    public function __construct($data = null)
    {
        $this->_date_created = date('Y-m-d H:i:s');
        if (is_array($data)) {
            $this->setFromArray($data);
        } else if (is_object($data)) {
            $this->setFromObject($data);
        }
    }
    
    /**
     * Required by Zend_Acl_Role_Interface
     * 
     * @return string
     */
    public function getRoleId()
    {
        $role = $this->getRole();
        return $role ? $role : 'guest';
    }

    /**
     * Required by Zend_Acl_Resource_Interface
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'user';
    }
    
    function setFromArray(array $data)
    {
        if(array_key_exists('username', $data)) {
            $this->_username = $data['username'];
        }
        if(array_key_exists('password', $data)) {
            $this->_password = $data['password'];
        }
        if(array_key_exists('role', $data)) {
            $this->_role = $data['role'];
        }
        if(array_key_exists('date_created', $data)) {
            $this->_date_created = $data['date_created'];
        }
    }

    function setFromObject($data)
    {
        if (isset($data->username)) {
            $this->_username = $data->username;
        }
        if (isset($data->password)) {
            $this->_password = $data->password;
        }
        if (isset($data->role)) {
            $this->_role = $data->role;
        }
        if (isset($data->date_created)) {
            $this->_date_created = $data->date_created;
        }
    }

    function getUsername()
    {
        return $this->_username;
    }
    
    function getPassword()
    {
        return $this->_password;
    }
    
    function getRole()
    {
        return $this->_role;
    }

    function getDateCreated()
    {
        return $this->_date_created;
    }
}
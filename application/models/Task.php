<?php

class Application_Model_Task implements Zend_Acl_Resource_Interface
{
    // metadata (properties)
    protected $_id;
    protected $_title;
    protected $_notes;
    protected $_due_date;
    protected $_date_completed;
    protected $_created_by;
    protected $_date_created;
    
    public function __construct($data = null)
    {
        $this->_date_created = date('Y-m-d H:i:s');
        if (is_array($data)) {
            $this->setFromArray($data);
        }
    }
    
    /**
     * Required by Zend_Acl_Resource_Interface
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'task';// . $this->getId();
    }
    
    function toArray()
    {
        $data['id'] = $this->_id;
        $data['title'] = $this->_title;
        $data['notes'] = $this->_notes;
        $data['due_date'] = $this->_due_date;
        $data['date_completed'] = $this->_date_completed;
        $data['created_by'] = $this->_created_by;
        $data['date_created'] = $this->_date_created;
        
        return $data;
    }
    
    function setFromArray(array $data)
    {
        if(array_key_exists('id', $data)) {
            $this->_id = $data['id'];
        }
        if(array_key_exists('title', $data)) {
            $this->_title = $data['title'];
        }
        if(array_key_exists('notes', $data)) {
            $this->_notes = $data['notes'];
        }
        if(array_key_exists('due_date', $data)) {
            $this->_due_date = $data['due_date'];
        }
        if(array_key_exists('date_completed', $data)) {
            $this->_date_completed = $data['date_completed'];
        }
        if(array_key_exists('created_by', $data)) {
            $this->_created_by = $data['created_by'];
        }
        if(array_key_exists('date_created', $data)) {
            $this->_date_created = $data['date_created'];
        }
    }
                       
    function getId()
    {
        return (int)$this->_id;
    }
    
    function getTitle()
    {
        return $this->_title;
    }
    
    function getNotes()
    {
        return $this->_notes;
    }
    
    function getDueDate()
    {
        return $this->_due_date;
    }
    
    function getDateCompleted()
    {
        return $this->_date_completed;
    }
    
    function getCreatedBy()
    {
        return $this->_created_by;
    }
    
    function getDateCreated()
    {
        return $this->_date_created;
    }
    
}
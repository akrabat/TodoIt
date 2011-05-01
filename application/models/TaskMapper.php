<?php

class Application_Model_TaskMapper
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_dbAdapter;
    
    protected $_tableName = 'tasks';
    
    public function setDbAdapter(Zend_Db_Adapter_Abstract $dbAdapter)
    {
        $this->_dbAdapter = $dbAdapter;
        return $this;
    }

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter()
    {
        if (null === $this->_dbAdapter) {
            $this->setDbAdapter(Zend_Db_Table::getDefaultAdapter());
        }
        return $this->_dbAdapter;
    }
    
    /**
     * @return Zend_Db_Select
     */
    protected function _select()
    {
        $select = $this->getDbAdapter()->select();
        $select->from($this->_tableName);
        
        return $select;
    }
    
    public function loadById($id) {
        $db = $this->getDbAdapter();
        $select = $db->select();
        $select->from($this->_tableName);
        $select->where('id = ?', (int)$id);
        $row = $db->fetchRow($select);
        if(!$row) {
            return false;
        }
        $task = new Application_Model_Task($row);
        return $task;
    }
    
    public function fetchRecentlyCompleted()
    {
        $db = $this->getDbAdapter();
        $select = $db->select();
        $select->from($this->_tableName);
        $select->where('date_completed IS NOT NULL');
        $select->order(array('date_completed DESC', 'id DESC'));
        $rows = $db->fetchAll($select);
        
        $tasks = array();
        foreach ($rows as $row) {
            $task = new Application_Model_Task($row);
            $tasks[] = $task;
        }
        return $tasks;
    }
    
    public function fetchOutstanding()
    {
        $db = $this->getDbAdapter();
        $select = $db->select();
        $select->from($this->_tableName);
        $select->where('date_completed IS NULL');
        $select->order(array('due_date ASC', 'id DESC'));
        $rows = $db->fetchAll($select);
        foreach ($rows as $row) {
            $task = new Application_Model_Task($row);
            $tasks[] = $task;
        }
        return $tasks;
    }    
    
    public function addTask(array $taskData)
    {
        $data = $this->_mapFromTaskArrayToDataArray($taskData);
        $this->insert($data);
    }

    public function updateTask($taskData, $id=null)
    {
        if (is_null($id) && isset($taskData['id'])) {
            $id = $taskData['id'];
        }
        $data = $this->_mapFromTaskArrayToDataArray($taskData);
        
        $this->update($data, 'id = ' . (int)$id);
    }
    
    /**
     * Translate from field names used in Application_Model_Task to the field
     * names used in the database table. At the moment, this is a one-to-one
     * mapping.
     * 
     * @param array $taskData
     * @return array
     */
    protected function _mapFromTaskArrayToDataArray(array $taskData)
    {
        $data['title'] = $taskData['title'];
        $data['notes'] = $taskData['notes'];

        // we don't want to assign an empty string to the date field
        $data['due_date'] = null;
        if ($taskData['due_date']) {
            $data['due_date'] = $taskData['due_date'];
        }
        $data['date_completed'] = null;
        if ($taskData['date_completed']) {
            $data['date_completed'] = $taskData['date_completed'];
        }
        $data['date_created'] = null;
        if ($taskData['date_created']) {
            $data['date_created'] = $taskData['date_created'];
        }
        
        return $data;
    }    
    
}
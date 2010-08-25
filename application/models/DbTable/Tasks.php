<?php

/**
 * Database storage for tasks. Note that task data is transferred to
 * and from the database as arrays. This TableGateway doesn't know
 * anything about the higher level abstractions of the data.
 * 
 * By convention loadXxx() methods return a single row and
 * fetchXxx() methods return an array of rows.
 *
 * @author rob (rob@akrabat.com)
 *
 */
class Application_Model_DbTable_Tasks extends Zend_Db_Table_Abstract
{
    protected $_name = 'tasks';

    public function loadById($id) {
        $row = $this->fetchRow('id = ' . (int)$id);

        return $row->toArray();
    }
    
    public function fetchRecentlyCompleted()
    {
        $select = $this->select();
        $select->where('date_completed IS NOT NULL');
        $select->order(array('date_completed DESC', 'id DESC'));
        $rows = $this->fetchAll($select);
        return $rows->toArray();
        
    }
    
    public function fetchOutstanding()
    {
        $select = $this->select();
        $select->where('date_completed IS NULL');
        $select->order(array('due_date ASC', 'id DESC'));
        $rows = $this->fetchAll($select);
        return $rows->toArray();
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

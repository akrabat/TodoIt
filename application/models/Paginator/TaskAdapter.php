<?php

class Application_Model_Paginator_TaskAdapter extends Zend_Paginator_Adapter_DbSelect
{
    /**
     * @var Application_Acl
     */
    protected $_acl;
    
    public function setAcl($acl) {
        $this->_acl = $acl;
    }
    /**
     * @var Application_Model_User
     */
    protected $_user;
    
    public function setUser($user) {
        $this->_user = $user;
    }
    
    /**
     * Returns an array of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $acl = $this->_acl;
        $user = $this->_user;
        $rows = parent::getItems($offset, $itemCountPerPage);
        
        $tasks = array();
        foreach ($rows as $row) {
            $task = new Application_Model_Task($row);
            
            // This is a 'hack' as now we don't have the correct number
            // of items per page...
            if ($acl->isAllowed($user, $task, 'read')) {
                $tasks[] = $task;
            } 
        }
        return $tasks;
    }
}

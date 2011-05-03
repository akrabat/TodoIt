<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->title = 'Outstanding tasks';
        $taskService = new Application_Service_TaskService();
        $this->view->outstandingTasks = $taskService->fetchOutstanding();
        $this->view->recentlyCompletedTasks = $taskService->fetchRecentlyCompleted();
        
        $messenger = $this->_helper->flashMessenger;
        $this->view->messages = $messenger->getMessages();
    }

    public function addAction()
    {
        $request = $this->getRequest();

        $form = new Application_Form_Task();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                // success
                $formData = $form->getValues();
                $taskService = new Application_Service_TaskService();
                $task = $taskService->create($formData);
                $this->_helper->flashMessenger->addMessage('Task added');
                $this->_helper->redirector('view', null, null, array('id'=>$task->id));
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $taskService = new Application_Service_TaskService();

        $form = new Application_Form_Task();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                // success
                $formData = $form->getValues();
                $task = $taskService->update($formData);
                $this->_helper->flashMessenger->addMessage('Task saved');
                $this->_helper->redirector('view', null, null, array('id'=>$task->id));
            }
        } else {
            $task = $taskService->loadById($request->getParam('id'));
            $form->populate($task->toArray());
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        // action body
    }

    public function viewAction()
    {
        $request = $this->getRequest();
        $taskService = new Application_Service_TaskService();
        $task = $taskService->loadById((int)$request->getParam('id'));
        if (!$task) {
            $this->_helper->redirector('index');
        }
        
        $this->view->task = $task;
    }


}



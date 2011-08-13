<?php

class Application_Plugin_ForceLogin extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance(); 
        if (!$auth->hasIdentity()) {
            $controller = $request->getControllerName();
            if ($controller != 'auth' && 
                $controller != 'error') {

                    $redirector = Zend_Controller_Action_HelperBroker::
                    getStaticHelper('redirector');
                    $redirector->gotoSimple('index', 'auth');
                }
        }
    }
}    

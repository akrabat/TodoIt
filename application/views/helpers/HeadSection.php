<?php

class Zend_View_Helper_HeadSection extends Zend_View_Helper_Abstract 
{
    public function headSection()
    {
        $view = $this->view;
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle('Todo List')->setSeparator(' - ');
        
        $html = $view->headMeta();
        $html .= $view->headTitle();
        
        return $html;
    }
}

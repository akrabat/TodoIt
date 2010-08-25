<?php

class Application_Form_Element_Date extends Zend_Form_Element_Xhtml
{
    public $helper = 'formDate';

    public function init ()
    {
        $this->addValidator(new Zend_Validate_Date());
    }
    
    public function isValid ($value, $context = null)
    {
        if (is_array($value)) {
            $value = $value['year'] . '-' .
                     $value['month'] . '-' .
                     $value['day'];
        }
        if($value == '--') {
            $value = null;
        }
        
        return parent::isValid($value, $context);
    }
}

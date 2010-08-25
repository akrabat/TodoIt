<?php

class Application_Form_Task extends Zend_Form
{
    public function init()
    {
        $title = new Zend_Form_Element_Text('title');
        $title->setRequired(true);
        $title->setLabel('Title');
        $title->addFilters(array(
            'StringTrim', 'StripTags'
            ));
        $title->addValidators(array(
            new Zend_Validate_StringLength(array('min'=>5))
            ));
        $this->addElement($title);
        
        $due = new Application_Form_Element_Date('due_date');
        $due->setRequired(false);
        $due->setLabel('Due');
        $this->addElement($due);

        $completed = new Zend_Form_Element_Checkbox('completed');
        $completed->setRequired(false);
        $completed->setLabel('Done?');
        $this->addElement($completed);
        
        $notes = new Zend_Form_Element_Textarea('notes');
        $notes->setRequired(false);
        $notes->setLabel('Notes');
        $this->addElement($notes);
        
        $element = new Zend_Form_Element_Submit('submit-button');
        $element->setIgnore(true);
        $element->setLabel('Add task');
        $this->addElement($element);
        
        $id = new Zend_Form_Element_Hidden('id');
        $this->addElement($id);
    }
    
    public function getValues($suppressArrayNotation = false)
    {
        $values = parent::getValues($suppressArrayNotation);
        if ($values['completed']) {
            $values['date_completed'] = date('Y-m-d H:i:s');
        }
        
        return $values;
    }
}


<?php

class Zend_View_Helper_FormDate extends Zend_View_Helper_FormElement 
{
    public function formDate ($name, $value = null, $attribs = null)
    {
        // if the element is rendered without a value,
        // show today's date
        $day = '';
        $month = '';
        $year = '';
        if (is_array($value)) {
            $day = $value['day'];
            $month = $value['month'];
            $year = $value['year'];
        } else {
            $t = strtotime($value);
            if ($t) {
                list($year, $month, $day) = explode('-', date('Y-m-d', $t));
            }
        }
        
        $date = new Zend_Date();
        
        $dayMultiOptions = array(''=>'');
        for ($i = 1; $i <= 31; $i ++) {
            $key = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dayMultiOptions[$key] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        
        $monthMultiOptions = array(''=>'');
        for ($i = 1; $i <= 12; $i ++) {
            $date->set($i, Zend_Date::MONTH);
            $key = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthMultiOptions[$key] = $date->toString('MMM');
        }
        
        $startYear = $date->toString('YYYY');
        $stopYear = $startYear + 10;
        $yearMultiOptions = array(''=>'');
        for ($i = $startYear; $i <= $stopYear; $i ++)
        {
            $yearMultiOptions[$i] = $i;
        }
        
        $dayAttribs = isset($attribs['dayAttribs']) ? $attribs['dayAttribs'] : array();
        $monthAttribs = isset($attribs['monthAttribs']) ? $attribs['monthAttribs'] : array();
        $yearAttribs = isset($attribs['yearAttribs']) ? $attribs['yearAttribs'] : array();
        
        
        return
            $this->view->formSelect(
                $name . '[day]',
                $day,
                $dayAttribs,
                $dayMultiOptions) . '&nbsp;' .
            $this->view->formSelect(
                $name . '[month]',
                $month,
                $monthAttribs,
                $monthMultiOptions) . '&nbsp;' .
            $this->view->formSelect(
                $name . '[year]',
                $year,
                $yearAttribs,
                $yearMultiOptions            
            );
    }
}

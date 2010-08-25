<?php

class Zend_View_Helper_FormDateFull extends Zend_View_Helper_FormElement
{
    public function formDateFull ($name, $value = null, $attribs = null)
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
        
        // build select options
        $dayAttribs = isset($attribs['dayAttribs']) ? $attribs['dayAttribs'] : array();
        $monthAttribs = isset($attribs['monthAttribs']) ? $attribs['monthAttribs'] : array();
        $yearAttribs = isset($attribs['yearAttribs']) ? $attribs['yearAttribs'] : array();

        
        $date = new Zend_Date();
        
        $dayMultiOptions = array(''=>'');
        for ($i = 1; $i < 32; $i ++)
        {
            $idx = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dayMultiOptions[$idx] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        
        $monthMultiOptions = array(''=>'');
        for ($i = 1; $i < 13; $i ++)
        {
            $date->set($i, Zend_Date::MONTH);
            $idx = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthMultiOptions[$idx] = $date->toString('MMMM');
        }
        
        $startYear = '2009';
        if (isset($attribs['startYear'])) {
            $startYear = $attribs['startYear'];
            if($startYear == 'now') {
                $startYear = date("Y");
            } else if($startYear{0} == '+') {
                $startYear = date("Y") + substr($stopYear, 1);
            } else if($startYear{0} == '-') {
                $startYear = date("Y") - substr($stopYear, 1);
            }
            unset($attribs['startYear']);
        }
        $stopYear = $startYear + 10;
        if (isset($attribs['stopYear'])) {
            $stopYear = $attribs['stopYear'];
            if($stopYear{0} == '+') {
                $stopYear = $startYear + substr($stopYear, 1);
            }
            unset($attribs['stopYear']);
        }
        $yearMultiOptions = array(''=>'');
        for ($i = $startYear; $i <= $stopYear; $i ++)
        {
            $yearMultiOptions[$i] = $i;
        }
        
        // return the 3 selects separated by &nbsp;
        
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

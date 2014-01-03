<?php
// Copyright (c) 2013 Andreas Ihrig (RoboMod) Licensed under the MIT license

class CalendarEntry extends DataObject{
    static $db = array(
        "Title" => "Text",
        "StartDate" => "SS_Datetime",
        "EndDate" => "SS_Datetime",
        "Linktitle" => "Text",
        "Link" => "Text",
		"Teaser" => "Text",
        "Description" => "Text"
    );

    static $has_one = array(
        "CalendarPage" => "CalendarPage",
        "Image" => "Image"
    );

    public static $summary_fields = array(
        "StartDate" => "StartDate",
        "Title" => "Title"
    );

    static $default_sort = "StartDate ASC";

    function getPermalink() {
        return CalendarPage::get()->byID($this->CalendarPageID)->AbsoluteLink('show/'.$this->ID);
    }

    function getCMSFields() {
        $startdatefield = new DatetimeField('StartDate','StartDate');
        $startdatefield->getDateField()->setConfig('showcalendar', true);
        $startdatefield->setConfig('datavalueformat', 'dd.MM.YYYY HH:mm');

        $enddatefield = new DatetimeField('EndDate','EndDate');
        $enddatefield->getDateField()->setConfig('showcalendar', true);
        $enddatefield->setConfig('datavalueformat', 'dd.MM.YYYY HH:mm');

        $imagefield = new UploadField('Image','Image (optional)');
        $imagefield->allowedExtensions = array('jpg', 'gif', 'png');

        $fields = new FieldList(
            new TextField('Title',"Event Title"),
            $startdatefield,
            $enddatefield,
            new TextField('Teaser',"Teaser"),
            new TextareaField('Description'),
            new TextField('Linktitle'),
            new TextField('Link'),
            $imagefield
        );

        $this->extend('updateCMSFields', $fields);		
        return $fields;		
    }

    function getCMSValidator() {
        return new RequiredFields('Title', 'StartDate', 'EndDate');
    }

    function getMonthDigit(){
        $date = strtotime($this->StartDate);
        return date('m',$date);
    }

    function getYear(){
        $date = strtotime($this->StartDate);
        return date('Y',$date);
    }
 	
    function isToday() {
        if ($this->EndDate) 
            return (date("Y-m-d") >= $this->StartDate && date("Y-m-d") <= $this->EndDate);
        return (date("Y-m-d") == $this->StartDate);
    }
}
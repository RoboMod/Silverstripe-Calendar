<?php
// Copyright (c) 2013 Andreas Ihrig (RoboMod) Licensed under the MIT license

class CalendarPage extends Page {

    private static $db = array(
        "ManageAllEvents" => "Boolean"
    );

    private static $has_many = array(
        "Events" => "CalendarEntry"
    );
    
    private static $has_one = array(
        'Pagelogo' => 'Image'
    );

    public function getFeeds() {
        // get all entries which still running
        $entries = CalendarEntry::get()->filter( array("CalendarPageID"=>$this->ID) )->Sort('StartDate, EndDate')
                ->where("EndDate >= CURRENT_DATE AND StartDate <= CURRENT_DATE")->limit(30);
        $feeds = new ArrayList();
        foreach ($entries as $entry) {
            $feed = new FeedEntry();
            $feed->Title = $entry->Title;
            $feed->Link = $entry->getPermalink();
            $feed->Description = $entry->Description;
            $feed->Content = 
                    $this->customise(
                            array('Image'=>Image::get()->filter(array("ID" => $entry->ImageID))->first(), 
                                'Text' => $entry->Description,
                                'StartDate' => $entry->StartDate,
                                'EndDate' => $entry->EndDate,
                                'Link' => $entry->Link,
                                'Linktitle' => $entry->Linktitle))
                    ->renderWith("CalendarFeed");
            $feed->Date = $entry->StartDate;
            $feed->Page = $this->Title;
            $feeds->push($feed);
        }

        return $feeds;
    }
    
    public function getLatest() {
        $where = "StartDate >= CURRENT_DATE OR (StartDate <= CURRENT_DATE AND EndDate >= CURRENT_DATE)";
        $entries = CalendarEntry::get()->filter( array("CalendarPageID" => $this->ID) )
                    ->Sort('StartDate', 'ASC')->limit(1)->where($where);
        
        return $this->customise(array('Entries' => $entries))->renderWith("CalendarStartPage");
    }
    
    function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->insertBefore(new Tab('Events'), 'Main');

        $config = GridFieldConfig_RecordEditor::create();
        $gridField = new GridField("Events", "Events", $this->Events(), $config);
        $fields->addFieldToTab("Root.Events", $gridField);

        $fields->addFieldToTab("Root.Conf", new CheckboxField("ManageAllEvents", "Manage all events?"));
        $fields->addFieldToTab("Root.Conf", new UploadField('Pagelogo'));
        
        return $fields;
    }

}

class CalendarPage_Controller extends Page_Controller {
    
    private static $allowed_actions = array(
        'json',
        'show'
    );
    
    public function init() {
        parent::init();
        
        i18n::set_locale('de_DE');
        setlocale (LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de_DE', 'de', 'ge');
        i18n::set_date_format('dd.MM.YYYY');
        i18n::set_time_format('HH:mm');

        Requirements::set_write_js_to_body(false);

        Requirements::javascript("framework/thirdparty/jquery/jquery.js");
        Requirements::javascript("calendar/3rdparty/jquery-ui-1.9.2.custom.js");
        Requirements::javascript("calendar/3rdparty/fullcalendar/fullcalendar.js");
        Requirements::javascript("calendar/3rdparty/jQuery-Loading/toggleLoading.jquery.js");
        Requirements::javascript("calendar/javascript/widget.js");

        Requirements::css("framework/thirdparty/jquery-ui-themes/smoothness/jquery-ui.css");
        Requirements::css("calendar/3rdparty/fullcalendar/fullcalendar.css");
        Requirements::css("calendar/css/calendar.css");
    }

    function Events() {
        $where = "StartDate >= CURRENT_DATE";
        $where .= " OR EndDate >= CURRENT_DATE";
        
        if (!$this->ManageAllEvents) {
            $filter =  array("CalendarPageID"=>$this->ID);
        }
        $entries = new PaginatedList(CalendarEntry::get()->filter( $filter )->Sort('StartDate')->where($where),
                $this->request);
        $entries->setPageLength(4);
        return $entries;
    }

    function json() {
        $params = $this->getURLParams();
        $filter = "(StartDate >= CURRENT_DATE";
        $filter .= " OR EndDate >= CURRENT_DATE)";
        if(array_key_exists('start', $params) && array_key_exists('start', $params) && is_numeric($params['start']) && is_numeric($params['end'])) {
            $filter = " AND (StartDate >= " . date('Y-m-d', $params['start']);
            $filter .= " AND StartDate <= " . date('Y-m-d', $params['end']);
            $filter .= " OR EndDate >= " . date('Y-m-d', $params['start']);
            $filter .= " AND EndDate <= " . date('Y-m-d', $params['end'] . ")");
        }
        $entries = CalendarEntry::get()->filter( array("CalendarPageID"=>$this->ID) )->Sort('StartDate')->where($filter);
        $result = array();
        foreach ($entries as $event) {
            array_push($result, 
                array(
                    'id' => $event->ID,
                    'title' => $event->Title,
                    'start' => $event->StartDate,
                    'end' => $event->EndDate,
                    'url' => $event->Link
                )
            );
        }
        echo json_encode($result);
    }
    
    function show() {
        $pagedevents = $this->Events();
        
        $params = $this->getURLParams();
        if(is_numeric($params['ID'])) {
            $page = array_search((int)$params['ID'], $pagedevents->column('ID'));
            if($page) {
                $pagedevents->setCurrentPage((int)($page/$pagedevents->getPageLength())+1);
                Director::redirect($this->Link().'?start='.$pagedevents->getPageStart());
            }
            else {
                Director::redirect($this->Link());
            }
        }
    }
}

?>
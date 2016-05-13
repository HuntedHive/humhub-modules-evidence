<?php

class EvidenceEvents{

    public static function onTopMenuInit($event)
    {
        $event->sender->addWidget('application.modules.evidence.widgets.EvidenceWidget', array(), array('sortOrder' => 90));
    }

}
<?php

class EvidenceEvents{

    public static function onTopMenuInit($event)
    {
        self::checkModulePosition();
        $event->sender->addWidget('application.modules.evidence.widgets.EvidenceWidget', array(), array('sortOrder' => 90));
    }

    public static function checkModulePosition()
    {
        if(Yii::app()->controller->id != "evidence" && isset($_COOKIE['LoadExport'])) {
//            setcookie("LoadExport", 0, time()-1, "/");
        }
    }
}
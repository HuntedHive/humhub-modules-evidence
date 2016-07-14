<?php

namespace humhub\modules\evidence;

use yii\base\Object;
use Yii;
use humhub\modules\evidence\widgets\EvidenceWidget;

class Events extends Object
{

    public static function onTopMenuInit($event)
    {
        self::checkModulePosition();
        $event->sender->addWidget(EvidenceWidget::className(), array(), array('sortOrder' => 90));
    }

    public static function checkModulePosition()
    {
        if(Yii::$app->controller->id != "evidence" && isset($_COOKIE['LoadExport'])) { //
//            setcookie("LoadExport", 0, time()-1, "/");
        }
    }
}
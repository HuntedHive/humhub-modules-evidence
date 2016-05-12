<?php

Yii::app()->moduleManager->register(array(
    'id' => 'evidence',
    'class' => 'application.modules.evidence.EvidenceModule',
    'import' => array(
        'application.modules.evidence.*',
        'application.modules.evidence.controllers.*',
        'application.modules.evidence.models.*',
        'application.modules_core.activity.models.*',
    ),

    'events' => array(
        array('class' => 'NotificationAddonWidget', 'event' => 'onInit', 'callback' => array('EvidenceEvents', 'onTopMenuInit')),
    ),
));
?>
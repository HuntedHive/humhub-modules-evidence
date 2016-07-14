<?php

use humhub\widgets\NotificationArea;

return [
    'id' => 'evidence',
    'class' => 'humhub\modules\evidence\Module',
    'namespace' => 'humhub\modules\evidence',
    'events' => array(
        array('class' => NotificationArea::className(), 'event' => NotificationArea::EVENT_INIT, 'callback' => array('humhub\modules\evidence\Events', 'onTopMenuInit')),
    ),
];
?>
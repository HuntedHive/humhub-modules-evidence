<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016  Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 *
 */

Yii::app()->moduleManager->register(array(
    'id' => 'evidence',
    'class' => 'application.modules.evidence.EvidenceModule',
    'import' => array(
        'application.modules.evidence.*',
        'application.modules.evidence.controllers.*',
        'application.modules.evidence.models.*',
        'application.modules_core.activity.models.*',
        'application.modules.evidence.lib.*',
    ),

    'events' => array(
        array('class' => 'NotificationAddonWidget', 'event' => 'onInit', 'callback' => array('EvidenceEvents', 'onTopMenuInit')),
    ),
));
?>

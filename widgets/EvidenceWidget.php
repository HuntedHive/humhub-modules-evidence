<?php

namespace humhub\modules\evidence\widgets;

use humhub\components\Widget;

/**
 * @package humhub.modules.mail
 * @since 0.5
 */
class EvidenceWidget extends Widget
{

    public function init()
    {
    }

    /**
     * Creates the Wall Widget
     */
    public function run()
    {
        return $this->render('evidenceView');
    }

}

?>
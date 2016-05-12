<?php

/**
 * @package humhub.modules.mail
 * @since 0.5
 */
class EvidenceWidget extends HWidget
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
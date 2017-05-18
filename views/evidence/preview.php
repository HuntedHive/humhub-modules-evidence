<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\evidence\models\CurrStepEvidence;
use humhub\modules\evidence\models\Evidence;

?>
<script type="text/javascript" src="<?php echo $this->context->module->assetsUrl; ?>/js/export.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->context->module->assetsUrl; ?>/css/evidence.css"/>

<div class="evidence-panel">
    <div class="container">

        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h4> <strong>Export</strong> my evidence </h4>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-right">
                        <btn class="btn btn-default" data-toggle="modal" data-target="#exportLoad"><i class="fa fa-folder-open-o fa-margin-right"></i> Load Saved Export</btn>
                        <btn class="btn btn-default" data-toggle="modal" data-target="#exportSave"><i class="fa fa-save fa-margin-right"></i> Save Export</btn>
                    </div>
                </div>
                <p><strong>Step 3 of 3</strong> - Preview evidence.<br>
                    <small>This is a preview of what will be printed out with the options to go back and edit, or export to docx.</small></p>
            </div>
        </div>

        <div class="row hidden-xs evidence-buttons-top">
            <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="<?= $previousUrl ?>"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Context</a> </div>
            <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary btn-export" href="#"><i class="fa fa-download fa-margin-right"></i> Export</a> </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?php $i=0; ?>
                <h4 class="text-right date-range"><strong>Date Range -</strong> <span class="previewdate"></span></h4>

                <!-- Output Preview -->

                <div style="text-align:right" hidden>
                    <div>Evidence Export</div>
                    <div class="previewdate"></div>
                </div>
                <?php foreach ($dataObjects as $itemK => $itemV) { ?>
                    <?php foreach ($itemV as $itemKey => $itemValue) { ?>
                        <?php ++$i; ?>
                        <div class="panel panel-default panel-contribution context-part" data-type="Answer_2">
                            <div class="panel-heading">
                                <small><?php echo Evidence::$iconObject[$itemKey]; ?>  Contribution <?= $i ?> - <?= Evidence::$acitvityType[$itemKey]; ?></small><br>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-context">
                                    <thead>
                                    <tr>
                                        <th>APST standard description</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong><?= Evidence::getOneAPSTS($itemValue['apsts'])['title'] ?></strong>
                                            <br>
                                            <?= Evidence::getOneAPSTS($itemValue['apsts'])['descr'] ?>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <thead>
                                    <tr>
                                        <th>Artefact to be used as evidence</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>
                                            <!-- --><?php //if(!empty($itemValue['mainObject'])): ?>
                                            <?= Evidence::getBody($itemValue['mainObject'], $itemKey); ?><br>

                                            <!-- --><?php //endif; ?>

                                            <!-- --><?php //if(!empty($itemValue['subObject'])): ?>
                                            <ul>
                                                <?= Evidence::getPreviewUlHtml($itemValue['subObject'], $itemKey); ?>
                                            </ul>
                                            <!-- --><?php //endif; ?>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <thead>
                                    <tr>
                                        <th>Description of how the artefact demonstrates impact upon teaching and/or student learning</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td class="note">
                                            <?php echo $itemValue['note']; ?>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <thead>
                                    <tr>
                                        <th>Description of how the artefact presented meets the standard described. <small>(Can be filled out later)</small></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td class="descr">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php  } ?>
                <?php  } ?>
                <!-- /.Output Preview -->

            </div>
        </div>

        <div class="row evidence-buttons evidence-buttons-bottom">
            <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="<?= $previousUrl ?>"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Context</a> </div>
            <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary btn-export" href="#"><i class="fa fa-download fa-margin-right"></i> Export</a> </div>
        </div>
        <div class="contentListOfItems">
            <?= (CurrStepEvidence::loadHtmlCookie())?CurrStepEvidence::loadHtmlCookie()->$step:""; ?>
        </div>
    </div>
</div>
<?= $this->render("_modals", ['step' => $step]); ?>
<script>
    var tableSaveExport = '<?= Url::toRoute("/evidence/evidence/save-to-word"); ?>';
</script>

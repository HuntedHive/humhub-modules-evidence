<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
?>
<script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/export.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl; ?>/css/evidence.css"/>

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

            <p class="text-right"><strong>Date Range -</strong> <span class="previewdate"></span></p>

            <!-- Output Preview -->
            <div class="table-responsive" >
                <div style="text-align:right" hidden>
                    <div>Evidence Export</div>
                    <div class="previewdate"></div>
                </div>
                <div class="grid-view">
                    <table class="items preview-evidence" style="border-collapse: collapse;">
                        <thead>
                        <tr style="background: #1895a4">
                            <th><span style="color:#ffffff">APST standard description.</span></th>
                            <th class="evidence"><span style="color:#ffffff">Artefact to be used as evidence.</span></th>
                            <th><span style="color:#ffffff">Description of how the artefact demonstrates impact upon teaching and/or student learning.</span></th>
                            <th class="supervisor-notes" rowspan="1"><span style="color:#ffffff">Description of how the artefact presented meets the standard described.
                                <small>(Can be filled out later)</small></span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataObjects as $itemKey => $itemValue) { ?>
                            <tr class="itemTr">
                                <td>
                                    <strong style="color:#1895a4;"><?= Evidence::getOneAPSTS($itemValue['apsts'])['title'] ?></strong>
                                    <br>
                                    <?= Evidence::getOneAPSTS($itemValue['apsts'])['descr'] ?>
                                </td>
                                <td class="text-left"><?php echo Evidence::$iconObject[$itemKey]; ?> <strong><?= Evidence::$acitvityType[$itemKey]; ?> -</strong> <i><?= Evidence::getBody($itemValue['mainObject'], $itemKey); ?></i>
                                    <ul>
                                        <?= Evidence::getPreviewUlHtml($itemValue['subObject'], $itemKey); ?>
                                    </ul>
                                </td>
                                <td class="note" style="width:50px"><?php echo $itemValue['note']; ?></td>
                                <td class="descr" style="width:50px"></td>
                            </tr>
                        <?php  } ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
<?= $this->renderPartial("_modals", ['step' => $step]); ?>
<script>
   var tableSaveExport = '<?= Yii::app()->createUrl("/evidence/evidence/saveToWord"); ?>';
</script>
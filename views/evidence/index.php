<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\evidence\models\Evidence;
use humhub\modules\evidence\models\CurrStepEvidence;

?>

<script type="text/javascript" src="<?php echo $this->context->module->assetsUrl; ?>/js/datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $this->context->module->assetsUrl; ?>/js/export.js"></script>
<script type="text/javascript" src="<?php echo $this->context->module->assetsUrl; ?>/js/main.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $this->context->module->assetsUrl; ?>/css/evidence.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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
                        <div class="row">
                            <div class="col-xs-12">
                                <p>This feature allows you to extract examples of your interaction within TeachConnect and produce a downloadable document as evidence of your achievement of the Australian Professional Standards for Teachers (APST) for inclusion in your professional teaching portfolio.</p>
                            </div>
                        </div>
                        <p><strong>Step 1 of 3</strong> - Select contributions.<br>
                            <small>Select the contributions you have made to the TeachConnect system within a selected date range.</small></p>
                    </div>
                </div>
                <div class="row evidence-buttons-top">
                    <div class="col-xs-12 col-sm-6 daterange">
                        <?php echo Html::beginForm(); ?>
                        <input name="daterange" type='text' class="daterangeobj form-control" placeholder="Select date range" value="<?= !empty($_POST['daterange'])?$_POST['daterange']:'' ?>">
                        <input type='submit' name='search' class="btn btn-success"  value="Search"/>
                        <?php echo Html::endForm(); ?>
                    </div>
                    <div class="hidden-xs col-sm-6 text-right">
                        <a class="btn btn-primary second-context" data-url="<?= Url::toRoute("/evidence/evidence/save-current-html") ?>" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive table-evidence-step1">
                            <?php \yii\widgets\Pjax::begin([
                                'id'=>'type_id',
                                'enablePushState' => false,
                            ]); ?>
                            <?php

                           echo \humhub\widgets\GridView::widget(array(
                                'dataProvider'=>$dataProvider,
                                'id'=>'customDataList',
                                'columns' => array(
                                    array(
                                        'attribute' => 'Select',
                                        'format' => 'raw',
                                        'value' => function($data) {
                                            return '<input class="itemSelect" data-type="'.$data['object_model'].'" type="checkbox" data-id="' . (isset($data['object_id'])?$data['object_id']:$data['id']) . '">';
                                        },
                                    ),
                                    array(
                                        'attribute' => 'Activity Date',
                                        'value' => function($data) {
                                            return date('d-M-y',strtotime($data['created_at']));
                                        },
                                    ),
                                    array(
                                        'format' => 'raw',
                                        'attribute' => 'Activity Type',
                                        'value' => function($data) {
                                            return Evidence::$iconObject[$data['object_model']] . Evidence::$acitvityType[$data['object_model']];
                                        },
                                    ),
                                    array(
                                        'attribute' => 'Text',
                                        'value' => function($data) {
                                            return Evidence::getText($data);
                                        },
                                    ),
                                    array(
                                        'format' => 'html',
                                        'attribute' => 'Target/Recipient',
                                        'value' => function($data) {
                                            return Evidence::getTarget($data);
                                        },
                                    ),
                                ),
                            ));
                            ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <a class="btn btn-primary second-context" data-url="<?= Url::toRoute("/evidence/evidence/save-current-html") ?>" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <?php echo Html::beginForm(
                        $stepUrl,
                        "post", [
                            'class' => 'listOfItems'
                        ]);
                    ?>
                    <div class="contentListOfItems">
                            <?= (CurrStepEvidence::loadHtmlCookie())?CurrStepEvidence::loadHtmlCookie()->$step:""; ?>
                    </div>

                    <?php echo Html::endForm() ?>
                </div>

<?= $this->render("_modals", ['step' => $step]); ?>

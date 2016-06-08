<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetPrefix);
$cs->registerScriptFile($assetPrefix2);
?>


<script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/export.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl; ?>/css/evidence.css"/>
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
                        <?php echo CHtml::beginForm(); ?>
                        <input name="daterange" type='text' class="form-control" placeholder="Select date range" value="<?= !empty($_POST['daterange'])?$_POST['daterange']:'' ?>">
                        <input type='submit' name='search' class="btn btn-success"  value="Search"/>
                        <?php echo CHtml::endForm(); ?>
                    </div>
                    <div class="hidden-xs col-sm-6 text-right">
                        <a class="btn btn-primary second-context" data-url="<?= Yii::app()->createUrl("evidence/evidence/saveCurrentHtml") ?>" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'dataProvider'=>$dataProvider,
                                'id'=>'customDataList',
                                'columns' => array(
                                    array(
                                        'name' => 'Select',
                                        'type' => 'raw',
                                        'value' => function($data) {
                                            return '<input class="itemSelect" data-type="'.$data['object_model'].'" type="checkbox" data-id="' . $data['id'] . '">';
                                        },
                                    ),
                                    array(
                                        'name' => 'Activity Date',
                                        'value' => function($data) {
                                            return $data['created_at'];
                                        },
                                    ),
                                    array(
                                        'name' => 'Activity Type',
                                        'value' => function($data) {
                                            return Evidence::$acitvityType[$data['object_model']];
                                        },
                                    ),
                                    array(
                                        'name' => 'Text (First xxx)',
                                        'value' => function($data) {
                                            return Evidence::getText($data);
                                        },
                                    ),
                                    array(
                                        'type' => 'raw',
                                        'name' => 'Target/Recipient',
                                        'value' => function($data) {
                                            return Evidence::getTarget($data);
                                        },
                                    ),
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <a class="btn btn-primary second-context" data-url="<?= Yii::app()->createUrl("evidence/evidence/saveCurrentHtml") ?>" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <?php echo CHtml::beginForm(
                        Yii::app()->createUrl('/evidence/evidence/sectionPrepareWord'),
                        "post", [
                            'class' => 'listOfItems'
                        ]);
                    ?>
                    <div class="contentListOfItems">
                            <?= (CurrStepEvidence::loadHtmlCookie())?CurrStepEvidence::loadHtmlCookie()->$step:""; ?>
                    </div>

                    <?php echo CHtml::endForm() ?>
                </div>

<?= $this->renderPartial("_modals", ['step' => $step]); ?>
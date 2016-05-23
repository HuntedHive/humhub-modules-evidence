<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetPrefix);
$cs->registerScriptFile($assetPrefix2);
?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl; ?>/css/evidence.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default evidence-panel">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h4>
                            <strong>Export</strong> my evidence
                        </h4>
                        <p><strong>Step 1 of 3</strong> - Select the data you would like to export and download to .doc format.</p>
                    </div>
                 </div>
                <div class="row">
                    <div class="col-xs-6">
                         <?php echo CHtml::beginForm(); ?>
                        <input name="daterange" type='text' class="form-control" value="<?= !empty($_POST['daterange'])?$_POST['daterange']:'' ?>">
                         <input type='submit' name='search' class="btn btn-success"  value="Search"/>
                         <?php echo CHtml::endForm(); ?>
                     </div>
                    <div class="col-xs-6 text-right">
                        <a class="btn btn-primary second-context" href="#">Next Step: Context</a>
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
                                            return '<input class="itemSelect" type="checkbox" data-id="' . $data['id'] . '">';
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
                        <a class="btn btn-primary second-context" href="#">Next Step: Context</a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <?php echo CHtml::beginForm(
                        Yii::app()->createUrl('/evidence/evidence/sectionPrepareWord'),
                        "post", [
                            'class' => 'listOfItems'
                        ]);
                    ?>
                    <?php echo CHtml::endForm() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetPrefix);
$cs->registerScriptFile($assetPrefix2);
?>
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default qanda-panel">
            <div class="panel-body">
                <div class="container">
                    <div class="col-sm-8">
                        <?php echo CHtml::beginForm(); ?>
                            <div class="row">
                                <div class='col-sm-6'>
                                    date from <?php echo CHtml::dateField("dateFrom", isset($_POST['dateFrom'])?$_POST['dateFrom']:"",['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-sm-6'>
                                    date to<?php echo CHtml::dateField("dateTo", isset($_POST['dateTo'])?$_POST['dateTo']:"",['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <input type='submit' name='search' class="btn btn-success"  value="Search"/>
                        <?php echo CHtml::endForm(); ?>
                    </div>
                    <div class="col-sm-4">
                        <?php echo CHtml::beginForm(
                            Yii::app()->createUrl('/evidence/evidence/sectionPrepareWord'),
                            "post", [
                                'class' => 'listOfItems'
                            ]);
                        ?>
                            <input type="submit" class="btn btn-success" value="Next step">
                        <?php echo CHtml::endForm() ?>
                    </div>
                </div>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'=>$dataProvider,
                    'id'=>'customDataList',
                    'columns' => array(
                        array(
                            'name' => 'select',
                            'type' => 'raw',
                            'value' => function($data) {
                                return '<input class="itemSelect" type="checkbox" data-id="' . $data['id'] . '">';
                            },
                        ),
                        array(
                            'name' => 'Activity date',
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
                            'name' => 'Text(First xxx)',
                            'value' => function($data) {
                                return Evidence::getText($data);
                            },
                        ),
                        array(
                            'name' => 'Target/recipient',
                            'value' => function($data) {
                                return Evidence::getTarget($data);
                            },
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
        <ul class="sul">
            <li>adasdas</li>
            <li>112312312</li>
            <li>
                <input type="textarea">
            </li>
        </ul>
    </div>
</div>
<link rel="stylesheet" type="text/css"
         href="<?php echo $this->module->assetsUrl; ?>/css/evidence.css"/>

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
            <a class="btn btn-primary" href="#">Date picker</a>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-primary" href="#">Next Step: Context</a>
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
                                return '<input type="checkbox" data-id="' . $data['id'] . '">';
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
        <br>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            <a class="btn btn-primary" href="#">Next Step: Context</a>
        </div>
    </div>
</div>
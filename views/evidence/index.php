<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default qanda-panel">
            <div class="panel-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'=>$dataProvider,
                    'id'=>'customDataList',
                    'columns' => array(
                        array(
                            'name' => 'select',
                            'type' => 'raw',
                            'value' => function($data) {
                                return '<input type="checkbox" data-id="' . $data['id'] . '">';
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
    </div>
</div>
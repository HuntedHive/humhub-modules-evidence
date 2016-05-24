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
    	<a class="btn btn-primary second-context" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
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
                        'type' => 'raw',
                        'value' => function($data) {
                            return '<i class="fa fa-dot-circle-o color-circle-mentorship fa-margin-right"></i>'.Evidence::$acitvityType[$data['object_model']];
                        },
                    ),
                    array(
                        'name' => 'Contribution Text <small>(First xxx characters)</small>',
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

  <div class="row evidence-buttons evidence-buttons-bottom">
    <div class="col-xs-12 text-right">
        <a class="btn btn-primary second-context" href="#">Next Step: Context <i class="fa fa-arrow-right fa-margin-right"></i></a>
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

<!-- Modal - Save Export -->
<div class="modal fade" id="exportSave" tabindex="-1" role="dialog" aria-labelledby="exportSave">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-margin-bottom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel"><i class="fa fa-save fa-margin-right"></i> Save Export</h3>
        <p>Save your export in order to modify and download at a later date.</p>
      </div>
      <div class="modal-body">
        <div class="col-xs-12">
            <input class="form-control" name="saveExport" id="saveExport" placeholder="Enter a name to reference your saved export" type="text">
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-12">
            <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save Export</button>
        </div>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<!-- Modal - Load Saved Export -->
<div class="modal fade" id="exportLoad" tabindex="-1" role="dialog" aria-labelledby="exportLoad">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-margin-bottom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel"><i class="fa fa-folder-open-o fa-margin-right"></i> Load Saved Export</h3>
        <p>Please select the saved export you would like to load.</p>
      </div>
      <div class="modal-body">
        <div class="col-xs-12">
            <div class="form-group">
                <select multiple class="form-select-multiple">
                  <option>Saved Export 1</option>
                  <option>Saved Export 2</option>
                  <option>Saved Export 3</option>
                  <option>Saved Export 4</option>
                  <option>Saved Export 5</option>
                </select>
              </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-12">
            <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Load Export</button>
        </div>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<!-- Modal - Select Teacher Type -->
<div class="modal fade" id="selectTeacherType" tabindex="-1" role="dialog" aria-labelledby="selectTeacherType">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-margin-bottom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Select Teacher Type</h3>
        <p>Please select your teacher type in order to gain access to the appropriate Australian Professional Standards for Teachers when exporting your evidence.</p>
        <p><small>You will be requested to update your teacher type once every 12 months.</small></p>
      </div>
      <div class="modal-body">
        <div class="form-group col-xs-12">
            <select name="teachertype" class="selectpicker form-control show-tick" required title="Select teacher type * ..." required>
                <optgroup label="Select teacher type *">
                  <option value="high school">high school</option>
                  <option value="primary school">primary school</option>
                  <option value="early childhood">experienced teacher</option>
                  <option value="other">other</option>
                </optgroup>
            </select>
        </div>
        <div class="hidden" id="teachertype-other">
            <div class="form-group col-xs-2 col-sm-1 indent-other">
                <i class="fa fa-arrow-right"></i>
            </div>
            <div class="form-group col-xs-10 col-sm-11">
                <input class="form-control" id="teachertype-other" placeholder="Enter 'other' teacher type *" name="teacherTypeOther" type="text" required>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-12">
            <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Save Teacher Type</button>
        </div>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

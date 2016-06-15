<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
$cs->registerScriptFile($assetPrefix);
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
        <p><strong>Step 2 of 3</strong> - Select context and standard.<br>
          <small>Link your previously selected contributions to some context and to one of the Australian Professional Standards for Teachers.</small></p>
      </div>
    </div>

    <div class="row hidden-xs evidence-buttons-top">
      <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="<?= $previousUrl ?>"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Contributions</a> </div>
      <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary second-context" data-url="<?= Yii::app()->createUrl("evidence/evidence/saveCurrentHtml") ?>" href="#">Next Step: Preview <i class="fa fa-arrow-right fa-margin-right"></i></a> </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <?php $i=0; ?>
        <?php foreach($dataObjects as $objectKey => $objectValue) { ?>
          <?php foreach($objectValue as $itemKeyContext => $itemContext) { ?>
            <?php ++$i; ?>
          <!-- Contribution Panel -->
          <div class="panel panel-default panel-contribution context-part" data-type="<?= $itemKeyContext."_".$itemContext['id'] ?>">
            <div class="panel-heading">
              <small>Contribution <?= $i ?></small><br>
              <i class="fa fa-comment fa-margin-right"></i> <?= Evidence::$acitvityType[$itemKeyContext]; ?> - <i><?= $itemContext['title']; ?></i></div>
            <div class="panel-body">
              <div class="form-group col-xs-12">
                <select name="apst" data-type="select" class="selectpicker form-control show-tick context-select" title="Select from Australian Professional Standards for Teachers * ..." required>
                  <optgroup label="Select from Australian Professional Standards for Teachers">
                      <?php foreach(Evidence::getFileAPSTS() as $apsts) { ?>
                      <option value="<?= $apsts['A'] ?>"><?= $apsts['B']; ?></option>
                      <?php } ?>
                  </optgroup>
                </select>
              </div>
              <div class="form-group col-xs-12">
                <textarea class="form-control context-textarea" data-type="textarea" placeholder="Add your notes on this evidence"></textarea>
              </div>
            </div>

            <!-- Contribution Panel Table -->
            <table class="table">
              <thead>
                <tr>
                  <th class="th-select text-center">Select</th>
                  <th>Context <small>(last 5 message responses)</small></th>
                </tr>
              </thead>
              <tbody>
                <?= Evidence::responseData($itemContext['context'], $itemKeyContext); ?>
              </tbody>
            </table>
            <!-- /.Contribution Panel Table -->

          </div data-type= data-type=>
          <!-- /.Contribution Panel -->
          <?php } ?>
        <?php } ?>

        <div class="row evidence-buttons evidence-buttons-bottom">
          <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="<?= $previousUrl ?>"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Contributions</a> </div>
          <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary second-context" data-url="<?= Yii::app()->createUrl("evidence/evidence/saveCurrentHtml") ?>" href="#">Next Step: Preview <i class="fa fa-arrow-right fa-margin-right"></i></a> </div>
        </div>
          <div class="col-sm-4">
              <?php echo CHtml::beginForm(
                  $stepUrl,
                  "post", [
                  'class' => 'listOfItems'
              ]);
              ?>
              <div class="contentListOfItems">
                  <?= (CurrStepEvidence::loadHtmlCookie())?CurrStepEvidence::loadHtmlCookie()->$step:""; ?>
              </div>

              <?php echo CHtml::endForm() ?>
          </div>
      </div>
    </div>

</div>

<script>
    var token = '<?= Yii::app()->request->csrfToken ?>';
    var url = '<?= Yii::app()->createUrl("/evidence/evidence/saveToWord") ?>';
</script>

<?= $this->renderPartial("_modals", ['step' => $step]); ?>
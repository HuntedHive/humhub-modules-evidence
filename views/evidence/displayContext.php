<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
$cs->registerScriptFile($assetPrefix);
$cs->registerScriptFile($assetPrefix2);
?>
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
      <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="#"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Contributions</a> </div>
      <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary second-context" href="#">Next Step: Preview <i class="fa fa-arrow-right fa-margin-right"></i></a> </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <?php $i=0; ?>
        <?php foreach($dataObjects as $objectKey => $objectValue) { ?>
          <?php foreach($objectValue as $itemKeyContext => $itemContext) { ?>
          <!-- Contribution Panel -->
          <div class="panel panel-default panel-contribution">
            <div class="panel-heading">
              <small>Contribution <?= ++$i ?></small><br>
              <i class="fa fa-comment fa-margin-right"></i> <?= Evidence::$acitvityType[$itemKeyContext]; ?> - <i><?= $itemContext['title']; ?></i></div>
            <div class="panel-body">
              <div class="form-group col-xs-12">
                <select name="apst" class="selectpicker form-control show-tick" title="Select from Australian Professional Standards for Teachers * ..." required>
                  <optgroup label="Select from Australian Professional Standards for Teachers">
                  <option value="APST_short_title 1">APST_short_title 1</option>
                  <option value="APST_short_title 2">APST_short_title 2</option>
                  <option value="APST_short_title 3">APST_short_title 3</option>
                  <option value="APST_short_title 4">APST_short_title 4</option>
                  <option value="APST_short_title 5">APST_short_title 5</option>
                  </optgroup>
                </select>
              </div>
              <div class="form-group col-xs-12">
                <textarea class="form-control" placeholder="Add your notes on this evidence"></textarea>
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
                <?php $j=0 ?>
                <?php foreach ($itemContext['context'] as $context) { ?>
                  <tr>
                    <td class="text-center"><input class="itemSelect" data-id="1" type="checkbox"></td>
                    <td> <strong>Response <?= ++$j; ?> -</strong> <?= $context->{Evidence::$contextParam[$itemKeyContext]}  ?> </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <!-- /.Contribution Panel Table -->

          </div>
          <!-- /.Contribution Panel -->
          <?php } ?>
        <?php } ?>

        <div class="row evidence-buttons evidence-buttons-bottom">
          <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="#"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Contributions</a> </div>
          <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary second-context" href="#">Next Step: Preview <i class="fa fa-arrow-right fa-margin-right"></i></a> </div>
          <button href="" class="btn btn-primary htmlToWord">Save To Word</button>
          <button href="" class="btn btn-primary htmlPreview">Preview</button>
        </div>

      </div>
    </div>

</div>

<script>
    var token = '<?= Yii::app()->request->csrfToken ?>';
    var url = '<?= Yii::app()->createUrl("/evidence/evidence/saveToWord") ?>';
</script>

<!-- Modal -->
<div class="modal previewModal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.Modal -->

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
      <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="#"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Context</a> </div>
      <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary" href="#"><i class="fa fa-download fa-margin-right"></i> Export</a> </div>
    </div>

    <div class="row">
      <div class="col-xs-12">

      <p class="text-right"><strong>Date Range -</strong> DD/MM/YYYY to DD/MM/YYYY</p>

        <!-- Output Preview -->
        <div class="table-responsive">
          <div class="grid-view">
            <table class="items preview-evidence">
              <thead>
                <tr>
                  <th>APST standard description.</th>
                  <th class="evidence">Artefact to be used as evidence.</th>
                  <th>Description of how the artefact demonstrates impact upon teaching and/or student learning.</th>
                  <th class="supervisor-notes">Description of how the artefact presented meets the standard described.
<small>(Can be filled out later)</small></th>
                </tr>
              </thead>
              <tbody>
                <tr class="odd">
                  <td><strong style="color:#1895a4;">[APST_short_title]</strong><br>
                    [APST_desc] </td>
                  <td class="text-left"><i class="fa fa-comment fa-margin-right"></i> <strong>Message -</strong> <i>A private message I have sent.</i><br>
                    <ul>
                      <li><strong>Response 1 -</strong> Lorem ipsum dolor sit amet. </li>
                      <li><strong>Response 2 -</strong> Lorem ipsum dolor sit amet. </li>
                      <li><strong>Response 3 -</strong> Lorem ipsum dolor sit amet. </li>
                      <li><strong>Response 4 -</strong> Lorem ipsum dolor sit amet. </li>
                      <li><strong>Response 5 -</strong> Lorem ipsum dolor sit amet. </li>
                    </ul>
                  </td>
                  <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</td>
                  <td></td>
                </tr>

                <tr class="even">
                  <td><strong>[APST_short_title]</strong><br>
                    [APST_desc] </td>
                  <td class="text-left"><i class="fa fa-stack-exchange fa-margin-right"></i> <strong>Community Post -</strong> <i>A question I posted in the Community Knowledge?</i><br>
                    <ul>
                      <li><strong>Answer 1 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Answer 2 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Answer 3 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Answer 4 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Answer 5 -</strong> Lorem ipsum dolor sit amet.</li>
                    </ul>
                  </td>
                  <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</td>
                  <td></td>
                </tr>

                <tr class="odd">
                  <td><strong>[APST_short_title]</strong><br>
                    [APST_desc] </td>
                  <td class="text-left"><i class="fa fa-stack-exchange fa-margin-right"></i> <strong>Community Response -</strong> <i>An answer I posted in the Community Knowledge.</i><br>
                    <ul>
                      <li><strong>Question -</strong> Lorem ipsum dolor sit amet. </li>
                      <li><strong>Comment 1 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Comment 2 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Comment 3 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Comment 4 -</strong> Lorem ipsum dolor sit amet.</li>
                    </ul>
                  </td>
                  <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</td>
                  <td></td>
                </tr>

                <tr class="even">
                  <td><strong>[APST_short_title]</strong><br>
                    [APST_desc] </td>
                  <td class="text-left"><i class="fa fa-dot-circle-o fa-margin-right"></i> <strong>Mentorship Circle Post -</strong> <i>A post I made in the mentorship circle.</i><br>
                    <ul>
                      <li><strong>Previous Message 1 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Previous Message 2 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Following Message 1 -</strong> Lorem ipsum dolor sit amet.</li>
                      <li><strong>Following Message 2 -</strong> Lorem ipsum dolor sit amet.</li>
                    </ul>
                  </td>
                  <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</td>
                  <td></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
        <!-- /.Output Preview -->

      </div>
    </div>

    <div class="row evidence-buttons evidence-buttons-bottom">
      <div class="col-xs-12 col-sm-6"> <a class="btn btn-primary" href="#"><i class="fa fa-arrow-left fa-margin-right"></i> Previous Step: Context</a> </div>
      <div class="col-xs-12 col-sm-6 text-right"> <a class="btn btn-primary" href="#"><i class="fa fa-download fa-margin-right"></i> Export</a> </div>
    </div>

</div>
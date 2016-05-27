<!-- Modal - Save Export -->
<div class="modal fade" id="exportSave" tabindex="-1" role="dialog" aria-labelledby="exportSave">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo CHtml::beginForm(Yii::app()->createUrl("/evidence/evidence/saveExport"), 'POST', ['id' => 'formSaveExport']); ?>
            <div class="modal-header modal-header-margin-bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel"><i class="fa fa-save fa-margin-right"></i> Save Export</h3>
                <p>Save your export in order to modify and download at a later date.</p>
            </div>

            <div class="displayErrors"></div>

            <div class="modal-body">
                <div class="col-xs-12">
                    <input class="form-control" name="saveExport" id="saveExport" placeholder="Enter a name to reference your saved export" type="text">
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                    <input type="button" class="btn btn-primary btn-sm buttonSaveExport" value="Save Export">
                </div>
            </div>

            <input type="hidden" name="step" value="<?= $step ?>">
            <?php echo CHtml::endForm(); ?>
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

            <div class="displayErrors"></div>
            
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="form-group">
                        <select multiple class="form-select-multiple loadExportSelect">
                            <?php
                                $listNames = ExportStepEvidence::getListNames();
                                foreach ($listNames as $name) {
                                    echo "<option value='$name->id'>$name->name</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                    <button type="button" class="btn btn-primary btn-sm loadExport" data-url="<?= Yii::app()->createUrl('/evidence/evidence/loadExport') ?>">Load Export</button>
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

<!-- Modal - Select Contributions Before Proceeding -->
<div class="modal fade modal-simple" id="selectContributions" tabindex="-1" role="dialog" aria-labelledby="selectContributions">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-margin-bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <h3>No Contributions Selected</h3>
                <p>Please select one or more contributions from the table<br> before proceeding to the next step.</p>
                <button type="button" class="btn btn-primary btn-md" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
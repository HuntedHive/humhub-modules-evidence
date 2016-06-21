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
                        <ul multiple class="form-select-multiple loadExportSelect list-group">
                            <?php
                                $listNames = ExportStepEvidence::getListNames();
                                foreach ($listNames as $name) {
                                    echo "<li data-value='$name->id' class='list-group-item' style='cursor:pointer;'>$name->name <i class='fa fa-times load-item-delete pull-right' title='delete' style='margin-top:3px;'></i></li>";
                                }
                            ?>
                        </ul>
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

<script>
    var deleteLoadExport = '<?= Yii::app()->createUrl("/evidence/evidence/deleteExport"); ?>';
    var CSRF_TOKEN = '<?= Yii::app()->request->csrfToken; ?>';
    var loadExport = '<?= Yii::app()->createUrl("/evidence/evidence/loadExport"); ?>';
</script>
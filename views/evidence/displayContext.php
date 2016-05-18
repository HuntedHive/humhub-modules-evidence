<?php
$assetPrefix = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/datetimepicker.js', true, 0, defined('YII_DEBUG'));
$assetPrefix2 = Yii::app()->assetManager->publish(Yii::getPathOfAlias("application") . '/modules/evidence/assets/js/main.js', true, 0, defined('YII_DEBUG'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
$cs->registerScriptFile($assetPrefix);
$cs->registerScriptFile($assetPrefix2);

?>

    <link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl; ?>/css/evidence.css"/>
<div class="mainWordContent">
    <?php
        echo $html;
    ?>
</div>
    <button href="" class="btn btn-primary htmlToWord">Save To Word</button>
    <button href="" class="btn btn-primary htmlPreview">Preview</button>
<script>
    var token = '<?= Yii::app()->request->csrfToken ?>';
    var url = '<?= Yii::app()->createUrl("/evidence/evidence/saveToWord") ?>';
</script>

<div class="modal previewModal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
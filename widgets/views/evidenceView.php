<?php
if(Yii::app()->controller->id == "profile") {
?>

    <script>
        $(document).ready(function() {
            var linkUrl = '<?= Yii::app()->createUrl("evidence/evidence/prepare"); ?>';
            console.log(linkUrl);
            var linkButton = "<a class='btn btn-primary' href='" + linkUrl + "'><i class='fa fa-download'></i> Export my evidence</a>&nbsp;";

            $(".controls-header a").before(linkButton);
        })
    </script>
<?php } ?>

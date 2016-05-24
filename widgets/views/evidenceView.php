<?php
$userGUID = User::model()->findByAttributes(['id' => Yii::app()->user->id])['guid'];
if(Yii::app()->controller->id == "profile" && $_GET['uguid'] == $userGUID) {
?>

    <script>
        $(document).ready(function() {
            var linkUrl = '<?= Yii::app()->createUrl("evidence/evidence/prepare"); ?>';

            var linkButton = "<a class='btn btn-primary' href='" + linkUrl + "'><i class='fa fa-download'></i> Export my evidence</a>&nbsp;";

            $(".controls-header a:first").before(linkButton);
        })
    </script>
<?php } ?>

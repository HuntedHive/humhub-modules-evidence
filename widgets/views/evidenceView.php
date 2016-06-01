<?php
$userGUID = User::model()->findByAttributes(['id' => Yii::app()->user->id])['guid'];
if(Yii::app()->controller->id == "profile" && $_GET['uguid'] == $userGUID) {
?>

    <script>
        $(document).ready(function() {

            var linkUrl = '<?= Yii::app()->createUrl("evidence/evidence/prepare"); ?>';

            var linkButton = "<a class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='This feature allows you to extract examples of your interaction within TeachConnect and produce a downloadable document as evidence of your achievement of the Australian Professional Standards for Teachers (APST) for inclusion in your professional teaching portfolio.' href='" + linkUrl + "'><i class='fa fa-download'></i> Export evidence</a>&nbsp;";

            $(".controls-header a:first").before(linkButton);
        })
    </script>
<?php } ?>

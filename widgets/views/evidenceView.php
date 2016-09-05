<?php
use Yii\helpers\Url;
use humhub\modules\user\models\User;
use humhub\modules\registration\models\ManageRegistration;
use Yii\helpers\Html;
?>
<?php
$userGUID = \Yii::$app->user->getIdentity()->guid;
if(\Yii::$app->controller->id == "profile" && $_GET['uguid'] == $userGUID) {
?>

    <script>
        $(document).ready(function() {

            var linkUrl = '<?= Url::toRoute("/evidence/evidence/prepare"); ?>';

            var linkButton = "<a class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='This feature allows you to extract examples of your interaction within TeachConnect and produce a downloadable document as evidence of your achievement of the Australian Professional Standards for Teachers (APST) for inclusion in your professional teaching portfolio.' href='" + linkUrl + "'><i class='fa fa-download'></i> Export evidence</a>&nbsp;";

            $(".controls-header a:first").before(linkButton);

        })
    </script>
<?php } ?>
<?php if(isset($_COOKIE['teacher_type_'.\Yii::$app->user->id]) && $_COOKIE['teacher_type_'.\Yii::$app->user->id] != "user_".\Yii::$app->user->id) { ?>
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
                    <?php echo Html::beginForm('', 'post', ['class' => 'form_teacher_type']) ?>
                    <div class="form-group col-xs-12">
                        <select name="teachertype" name="teacherType" class="selectpicker form-control show-tick modal_teacher_type" required title="Select teacher type * ..." required>
                            <optgroup label="Select teacher type *">
                                <?php

                                    $types = ManageRegistration::find()->andWhere('type=' . ManageRegistration::TYPE_TEACHER_TYPE . ' AND `default`=' . ManageRegistration::DEFAULT_ADDED)->all();
                                    foreach ($types as $item) {
                                        echo "<option value='$item->name'>$item->name</option>";
                                    }

                                ?>
                                <option value="other">other</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="" id="teachertype-other" hidden>
                        <div class="form-group col-xs-2 col-sm-1 indent-other">
                            <i class="fa fa-arrow-right custom-right-arrow"></i>
                        </div>
                        <div class="form-group col-xs-10 col-sm-11">
                            <input class="form-control" id="teachertype-other" placeholder="Enter 'other' teacher type *" name="teacherTypeOther" type="text" required>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary btn-sm btn-primary-modal">Save Teacher Type</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
    <script>
        $(document).ready(function() {
            $("#selectTeacherType").modal('show');

            $("#selectTeacherType .close").on("click", function() {
                $("#selectTeacherType").modal('hide');
                setCookie("teacher_type_"+<?= \Yii::$app->user->id ?>, "user_"+<?= \Yii::$app->user->id ?>);
            });

            function setCookie(key, value) {
                var expires = new Date();
                var day = 24*60*60*1000; // one day
                var month = day*30; // one month
                var year = month * 12; // One year or 12 moth
                expires.setTime(expires.getTime() + (year)); //12 month
                document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
            }

            $(".btn-primary-modal").on("click", function() {
                var form = $(".form_teacher_type").serialize();
                $.ajax({
                    url: "<?php echo Url::toRoute("/registration/registration/updateUserTeacherType"); ?>",
                    data: form,
                    type: 'POST',
                    success: function(data) {
                        setCookie("teacher_type_"+<?= \Yii::$app->user->id ?>, "user_"+<?= \Yii::$app->user->id ?>);
                        window.location.href = "";
                    }
                });
                return false;
            });

            $(".modal_teacher_type").change(function() {
                var option = $(this).val();
                if(option == "other") {
                    $("#teachertype-other").fadeIn(1);
                } else {
                    $("#teachertype-other").fadeOut(1);
                    $("#teachertype-other input").val('');
                }
            });
        });
    </script>
<?php } ?>

<?php if(!isset($_COOKIE['teacher_type_'.\Yii::$app->user->id])) { ?>
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
                    <?php echo Html::beginForm('', 'post', ['class' => 'form_teacher_type']) ?>
                        <div class="form-group col-xs-12">
                            <select name="teachertype" name="teacherType" class="selectpicker form-control show-tick modal_teacher_type" required title="Select teacher type * ..." required>
                                <optgroup label="Select teacher type *">
                                    <?php
                                        $types = ManageRegistration::find()->andWhere('type='.ManageRegistration::TYPE_TEACHER_TYPE . ' AND `default`='. ManageRegistration::DEFAULT_ADDED)->all();
                                        foreach ($types as $item) {
                                            echo "<option value='$item->name'>$item->name</option>";
                                        }
                                    ?>
                                    <option value="other">other</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="" id="teachertype-other" hidden>
                            <div class="form-group col-xs-2 col-sm-1 indent-other">
                                <i class="fa fa-arrow-right custom-right-arrow"></i>
                            </div>
                            <div class="form-group col-xs-10 col-sm-11">
                                <input class="form-control" id="teachertype-other" placeholder="Enter 'other' teacher type *" name="teacherTypeOther" type="text" required>
                            </div>
                        </div>
                    <?php echo Html::endForm(); ?>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary btn-sm btn-primary-modal">Save Teacher Type</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->

    <script>
        $(document).ready(function() {
            $("#selectTeacherType").modal('show');

            $("#selectTeacherType .close").on("click", function() {
                $("#selectTeacherType").modal('hide');
                setCookie("teacher_type_"+<?= \Yii::$app->user->id ?>, "user_"+<?= \Yii::$app->user->id ?>);
            });

            function setCookie(key, value) {
                var expires = new Date();
                var day = 24*60*60*1000; // one day
                var month = day*30; // one month
                var year = month * 12; // One year or 12 moth
                expires.setTime(expires.getTime() + (year)); //12 month
                document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
            }

            $(".btn-primary-modal").on("click", function() {
                var form = $(".form_teacher_type").serialize();
                $.ajax({
                    url: "<?php echo Url::toRoute("/registration/registration/update-user-teacher-type"); ?>",
                    data: form,
                    type: 'POST',
                    success: function(data) {
                        setCookie("teacher_type_"+<?= \Yii::$app->user->id ?>, "user_"+<?= \Yii::$app->user->id ?>);
                        window.location.href = "";
                    }
                });
                return false;
            });

            $(".modal_teacher_type").change(function() {
                var option = $(this).val();
                if(option == "other") {
                    $("#teachertype-other").fadeIn(1);
                } else {
                    $("#teachertype-other").fadeOut(1);
                    $("#teachertype-other input").val('');
                }
            });
        });
    </script>
<?php } ?>
<?php

namespace humhub\modules\evidence\models;

use humhub\modules\questionanswer\models\Question;
use yii\helpers\Html;
use humhub\modules\activity\models\Activity;
use humhub\modules\content\models\Content;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\post\models\Post;
use humhub\modules\questionanswer\models\Answer;
use humhub\modules\registration\models\ManageRegistration;
use humhub\modules\user\models\Profile;
use humhub\modules\user\models\User;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use Yii;

class Evidence extends Object
{
    private static $data;
    private static $_instance;
    private static $wordText;
    private static $docx;

    public static $acitvityType = [
        'Post' => 'Mentorship Circle Post',
        'Question' => 'Community post',
        'Answer' => 'Community response',
        'MessageEntry' => 'Message',
    ];

    public static $relationObject = [
        'Post' => 'Post',
        'Question' => 'Question',
        'Answer' => 'Answer',
        'MessageEntry' => 'MessageEntry',
    ];

    public static $relationPreview = [
        'Post' => 'Post',
        'Question' => 'Question',
        'Answer' => 'Answer',
        'MessageEntry' => 'MessageEntry',
    ];

    public static $contextMess = [
        'Post' => '(the 2 messages either side of post in circle)',
        'Question' => '(top 5 answers)',
        'Answer' => '(the question and up to 4 comments)',
        'MessageEntry' => '(last 5 message responses)',
    ];

    public static $iconObject = [
        'Post' => '<i class="fa fa-dot-circle-o fa-margin-right"></i>',
        'Question' => '<i class="fa fa-stack-exchange fa-margin-right"></i>',
        'Answer' => '<i class="fa fa-stack-exchange fa-margin-right"></i>',
        'MessageEntry' => '<i class="fa fa-comment fa-margin-right"></i>',
    ];

    public static $contextParam = [
        'Post' => 'message',
        'Question' => 'post_text',
        'Answer' => 'post_title',
        'MessageEntry' => 'content',
    ];

    public static function instance()
    {
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getAllQuery()
    {
        $period= '';
        if(isset($_POST['daterange'])) {
            $from = $_POST['date_from'];
            $to = $_POST['date_to'];
            $period = " AND created_at >= '$from' AND created_at <= '$to'";
        }
        $sql = "SELECT * 
                FROM `content`
                WHERE 
                    `object_model` != 'humhub\\\modules\\\chat\\models\\\WBSChat'
                      AND
                    `object_model` != 'humhub\\\modules\\\questionanswer\\\models\\\QAComment'
                      AND
                    `object_model` != 'humhub\\\modules\\\comment\\\models\\\Comment'
                      AND
                    `object_model` != 'humhub\\\modules\\\cfiles\\\models\\\Folder'
                      AND
                    `object_model` != 'humhub\\\modules\\\cfiles\\\models\\\File'
                      AND
                    `object_model` != 'humhub\\\modules\\\comment\\\models\\\Comment'
                      AND
                    `object_model` != 'humhub\\\modules\\\\tasks\\\models\\\Task'
                      AND
                    `object_model` != 'humhub\\\modules\\\polls\\\models\\\Poll'
                      AND
                    `object_model` != 'humhub\\modules\\user\\models\\Follow'
                      AND
                    `created_by` =" . Yii::$app->user->id
            .$period;

        self::$data = Yii::$app->db->createCommand($sql)->queryAll();
        return $this;
    }

    public function filterActivity()
    {
        foreach (self::$data as $key => $value) {
            if($value['object_model'] == "humhub\modules\activity\models\Activity") {
                $activity = Activity::find()->andWhere(['id' => $value['object_id']])->one();

                if(!empty($activity) && in_array($activity->module, ["user", "polls", "like", "comment", "space"])) {

                    unset(self::$data[$key]);
                } else {

                    $actExp = explode("\\" , $activity->object_model);
                    $act = end($actExp);
                    if ($activity->module == "questionanswer" && $act == "QAComment") {
                        unset(self::$data[$key]);
                    } else {
                        self::$data[$key]['object_model'] = $act;
                        self::$data[$key]['object_id'] = $activity->object_id;
                    }

                    if($act == "Post" && empty(self::$data[$key]['space_id'])) {
                        unset(self::$data[$key]);
                    }
                }
                $actExp = explode("\\" , $activity->class);
                $act = end($actExp);
                if($act == "ChatMessage" || $act == "KnowledgeCommentCreated" || $act == "Follow") {
                    unset(self::$data[$key]);
                }
            } else {
                unset(self::$data[$key]);
            }

//            if($value['object_model'] == "humhub\modules\post\models\Post") {
//                self::$data[$key]['object_model'] = "Post";
//            }
//
//            if($value['object_model'] == "humhub\modules\questionanswer\models\Question") {
//                self::$data[$key]['object_model'] = "Question";
//            }
//
//            if($value['object_model'] == "humhub\modules\questionanswer\models\Answer") {
//                self::$data[$key]['object_model'] = "Answer";
//            }
//
//            if($value['object_model'] == "humhub\modules\post\models\Post" && empty(self::$data[$key]['space_id'])) {
//                unset(self::$data[$key]);
//            }
        }

        return $this;
    }

    public function addEntryMessageActivity()
    {
        $period= '';
        if(isset($_POST['daterange'])) {
            $from = $_POST['date_from'];
            $to = $_POST['date_to'];
            $period = " AND created_at >= '$from' AND created_at <= '$to'";
        }
        $sql = 'SELECT * 
                FROM message_entry 
                WHERE  
                    created_by =' . Yii::$app->user->id
            .$period;
        $dataMessages = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($dataMessages as $key => $value) {
            $dataMessages[$key]['object_model'] = 'MessageEntry';
        }
        self::$data = array_merge(self::$data, $dataMessages);
        if(!empty(self::$data)) {
            $this->sksort(self::$data, "created_at");
        }
        return $this;
    }

    private function sksort(&$array, $subkey="id", $sort_ascending=false) {

        if (count($array))
            $temp_array[key($array)] = array_shift($array);

        foreach($array as $key => $val){
            $offset = 0;
            $found = false;
            foreach($temp_array as $tmp_key => $tmp_val)
            {
                if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
                {
                    $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                        array($key => $val),
                        array_slice($temp_array,$offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
        }

        if ($sort_ascending) $array = array_reverse($temp_array);

        else $array = $temp_array;
    }


    public function getData() {
        return self::$data;
    }

    public static function getText($object)
    {
        $switch = self::$relationObject[$object['object_model']];
        switch($switch) {
            case 'Post':
                return Post::findOne($object['object_id'])->message;
                break;
            case 'Question':
                $entity = Question::findOne($object['object_id']);
                if(!empty($entity)){
                    return $entity->post_text;
                }
                break;
            case 'Answer':
                return Answer::findOne($object['object_id'])->post_text;
                break;
            case 'MessageEntry':
                return MessageEntry::findOne($object['id'])->content;
                break;
        }

        return null;
    }

    public static function getPrepareObjects($data)
    {
        $listActivity = $data;
        $html = [];
        foreach ($listActivity as $objectActivityKey => $objectActivityValue) {
            $html = array_merge(self::getObjectData($objectActivityKey, $objectActivityValue), $html);
        }

        return $html;
    }

    public static function getObjectData($objectKey, $objectValues)
    {
        $subData = [];
        foreach ($objectValues as $objectValue) {
            switch ($objectKey) {
                case 'Post': // model is Post in db
                    $content = Content::find()->andWhere(['object_model' => 'humhub\modules\post\models\Post', 'object_id' => $objectValue])->one();
                    $mainObject = Post::findOne($objectValue);

                    if(!empty($mainObject) && !empty($content->space_id)) {
                        $lastContentPosts = Content::find()->andWhere('object_id >=' . ($mainObject->id - 2) . ' AND object_id<=' . ($mainObject->id + 2) . ' AND object_id!=' . ($mainObject->id) . ' AND object_model = "humhub\\\modules\\\post\\\models\\\Post" AND space_id=' . $content->space_id)->all();
                        $result = !empty($lastContentPosts) ? implode(",", ArrayHelper::map($lastContentPosts, 'object_id', 'object_id')) : 0;
                        $subObject = Post::find()->andWhere('id IN (' . $result . ')')->all();
                        $subData[] = [
                            $objectKey => [
                                'id' => $mainObject->id,
                                'title' => $mainObject->message,
                                'context' => $subObject,
                            ]
                        ];
                    }

                    break;
                case 'Question':
                    $mainObject = Question::findOne($objectValue);
                    if(!empty($mainObject)) {
                        $subObject = Answer::find()->andWhere('question_id = ' . $mainObject->id . ' AND post_type = "answer" ORDER BY created_at DESC LIMIT 5')->all();
                        $subData[] = [
                            $objectKey => [
                                'id' => $mainObject->id,
                                'title' => $mainObject->post_text,
                                'context' => $subObject,
                            ]
                        ];
                    }

                    break;
                case 'Answer':
                    $mainObject = Answer::findOne($objectValue);
                    if(!empty($mainObject)) {
                        $questionObject = Answer::findOne($mainObject->question_id);
                        $subObject = Answer::find()->andWhere('parent_id = ' . $mainObject->id . ' AND post_type = "comment" ORDER BY created_at DESC LIMIT 5')->all();
                        $subData[] = [
                            $objectKey => [
                                'id' => $mainObject->id,
                                'title' => $mainObject->post_text,
                                'context' => array_merge([$questionObject], $subObject),
                            ]
                        ];
                    }

                    break;
                case 'MessageEntry':
                    $mainObject = MessageEntry::findOne($objectValue);
                    if(!empty($mainObject)) {
                        $preCount = 5;
                        $subObject = MessageEntry::find()->andWhere(['message_id' => $mainObject->message_id])->orderBy(['created_at' => SORT_ASC])->limit(5)->all();
                        $subData[] = [
                            $objectKey => [
                                'id' => $mainObject->id,
                                'title' => $mainObject->content,
                                'context' => $subObject,
                            ]
                        ];
                    }

                    break;
            }
        }

        return $subData;
    }

    public static function getTarget($object)
    {
        $switch = self::$relationObject[$object['object_model']];
        switch($switch) {
            case 'Answer':
                $answer = Answer::findOne($object['object_id']);
                if(!empty($answer)) {
                    $question = Answer::findOne($answer->question_id);
                    $user = User::findOne($question->created_by);
                    if (isset($user->username)) {
                        return $user->username;
                    } else {
                        return "-";
                    }
                }
                return "-";
                break;
        case 'MessageEntry':
            $groupMessages = ArrayHelper::map(UserMessage::find()->andWhere('user_id !='.Yii::$app->user->id. ' AND message_id=' . $object['message_id'])->all(),"user_id", "user_id");
            $users = User::find()->andWhere('id IN (' . implode(",", $groupMessages) . ')')->all();
            if(!empty($users)) {
                $usernames = implode("<br />" , ArrayHelper::map($users, "username", "username"));
                return $usernames;
            }
            return "-";
            break;
        default:
            return "-";
    }
}

    public function saveWord()
    {
        $path = Yii::getAlias('@webroot') . '/uploads/file/evidenceDoc_' . Yii::$app->user->id;
        self::$docx->modifyPageLayout('A4-landscape');
        self::$docx->createDocx($path);
        $absolutePath = Yii::$app->request->hostInfo . "/". Yii::$app->request->baseUrl . "/uploads/file/evidenceDoc_".Yii::$app->user->id.".docx";
        return $absolutePath;
    }

    public function prepareHtmlToHtml($html)
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "lib/phpdocx/classes/CreateDocx.inc";

        self::$docx = new \CreateDocx();
        self::$docx->setDefaultFont('Arial');
        $options = array(
            'src' => Yii::getAlias("@webroot").'/themes/humhub-themes-tq/img/teachconnect-logo-header.png',
            'imageAlign' => 'left',
            'scaling' => 25,
            'textWrap' => 0,
        );
        self::$docx->addImage($options);

        self::$docx->embedHTML($html);

        $options = array(
            'src' => Yii::getAlias("@webroot").'/themes/humhub-themes-tq/img/teachconnect-logo-footer.png',
            'imageAlign' => 'right',
            'scaling' => 25,
            'spacingTop' => 10,
            'spacingBottom' => 0,
            'spacingLeft' => 0,
            'spacingRight' => 20,
            'textWrap' => 0,
            'border' => 0,
        );
        self::$docx->addImage($options);
        return $this;
    }

    public static function getPreparePreivew($data)
    {
        $listActivity = $data;
        $objects = [];

        foreach ($listActivity as $TypeAndId => $arrayData) {
            $objects[] = self::getObjectPrepareData($TypeAndId, $arrayData);
        }
        return $objects;
    }

    private static function getObjectPrepareData($TypeAndId, $arrayData) {
        $subData = [];
        $mainKey = explode("_", $TypeAndId)[0];
        $mainId = explode("_", $TypeAndId)[1];
        switch ($mainKey) {
            case 'Post': // model is Post in db
                $nameRelation = self::$relationPreview[$mainKey];

                $mainObject = Post::findOne($mainId);
                $note = isset($arrayData['textarea']) ? $arrayData['textarea'] : '';
                $apsts = isset($arrayData['select']) ? $arrayData['select'] : [];

                if(!empty($mainObject)) {
                    $subObject = Post::find()->andWhere('id IN(' . (implode(",", (isset($arrayData['checkbox'])) ? $arrayData['checkbox'] : [0])) . ')')->all();
                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                }

                break;
            case 'Question':
                $nameRelation = self::$relationPreview[$mainKey];

                $mainObject = Question::findOne($mainId);
                $note = isset($arrayData['textarea'])?$arrayData['textarea']:'';
                $apsts = isset($arrayData['select'])?$arrayData['select']:[];

                if(!empty($mainObject)) {
                    $subObject = Answer::find()->andWhere('id IN(' . (implode(",", (isset($arrayData['checkbox']))?$arrayData['checkbox']:[0])) . ')')->all();
                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                }

                break;
            case 'Answer':
                $nameRelation = self::$relationPreview[$mainKey];
                $mainObject = Answer::findOne($mainId);
                $note = isset($arrayData['textarea']) ? $arrayData['textarea'] : '';
                $apsts = isset($arrayData['select']) ? $arrayData['select'] : [];

                if(!empty($mainObject)) {
                    $subObject = Answer::find()->andWhere('id IN(' . (implode(",", (isset($arrayData['checkbox'])) ? $arrayData['checkbox'] : [0])) . ')')->all();

                    $subQuestion = NULL;
                    if (!empty($arrayData['checkbox_question'])) {
                        $subObject['question'] = Answer::findOne($arrayData['checkbox_question'][0]);
                    }

                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                }

                break;
            case 'MessageEntry':
                $nameRelation = self::$relationPreview[$mainKey];

                $mainObject = MessageEntry::findOne($mainId);
                $note = isset($arrayData['textarea']) ? $arrayData['textarea'] : '';
                $apsts = isset($arrayData['select']) ? $arrayData['select'] : [];

                if(!empty($mainObject)) {

                    $subObject = MessageEntry::find()->andWhere('id IN(' . (implode(",", (isset($arrayData['checkbox'])) ? $arrayData['checkbox'] : [0])) . ')')->all();

                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                }

                break;
        }

        return $subData;
    }

    public static function responseData($itemContext, $itemKeyContext)
    {
        $j=0;
        $result = "";
        switch($itemKeyContext) {
            case 'Post': // model is Post in db
                $i=1;
                foreach ($itemContext as $context) {
                    if(!empty($context)) {
                        $firstname = Yii::$app->user->getIdentity()->username;
                        $preWord = ($i == 3) ? "Previous Message" : "Following Message";
                        ($i == 3) ? $i = 1 : '';
                        $result .= "<tr>";
                        $result .= "<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                        $result .= "<td> <strong>" . $preWord . " " . $i . " (" . Html::encode($firstname) . ")-</strong> " . Html::encode($context->{Evidence::$contextParam[$itemKeyContext]}) . "</td>";
                        $result .= "</tr>";
                        $i++;
                    }
                }
                return $result;
                break;
            case 'Question':
                foreach ($itemContext as $context) {
                    if(!empty($context)) {
                        $result .= "<tr>";
                        $result .= "<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                        $result .= "<td> <strong>Answer " . (++$j) . "-</strong> " . Html::encode($context->{Evidence::$contextParam[$itemKeyContext]}) . "</td>";
                        $result .= "</tr>";
                    }
                }
                return $result;
                break;
            case 'Answer':
                $i = 0;
                foreach ($itemContext as $context) {
                    if(!empty($context)) {
                        if ($i == 0) {
                            $text = $context->{Evidence::$contextParam[$itemKeyContext]};
                            $preWord = "Question";
                            $type = "checkbox_question";
                        } else {
                            $text = $context->post_text;
                            $preWord = "Comment " . $i;
                            $type = "checkbox";
                        }
                        $result .= "<tr>";
                        $result .= "<td class='text-center'><input class='itemSelect context-checkbox' data-type='" . $type . "' data-id='$context->id' type='checkbox'></td>";
                        $result .= "<td><strong>" . $preWord . " - </strong> " . Html::encode($text) . "</td>";
                        $result .= "</tr>";
                        $i++;
                    }
                }
                return $result;
                break;
            case 'MessageEntry':
                foreach ($itemContext as $context) {
                    $result.="<tr>";
                    $result.="<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                    $result.="<td> <strong>Response " . (++$j) . "-</strong> ". Html::encode($context->{Evidence::$contextParam[$itemKeyContext]}) ."</td>";
                    $result.="</tr>";
                }
                return $result;
                break;
        }
    }

    public static function getPreviewUlHtml($itemValue, $itemKey)
    {
        $html= "";

        if(empty($itemValue) || empty($itemKey)) {
            return $html;
        }

        switch($itemKey) {
            case 'Post': // model is Post in db
                $i=1;
                foreach ($itemValue as $subItem) {
                    $firstname = Yii::$app->user->getIdentity()->username;
                    $preWord = ($i==3)?"Previous Message":"Following Message";
                    ($i==3)?$i=1:'';
                    $html.="<li><strong>$preWord ". ($i) . " (" . Html::encode($firstname) .") - </strong>".  Html::encode($subItem->{self::$contextParam[$itemKey]}) ."</li>";
                    $i++;
                }
                return $html;
                break;
            case 'Question':
                $i=0;
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Answer ". (++$i) . " - </strong>".  Html::encode($subItem->{self::$contextParam[$itemKey]}) ."</li>";
                }
                return $html;
                break;
            case 'Answer':
                $i = 0;
                if(isset($itemValue['question'])) {
                    $question = $itemValue['question'];
                    $html .= "<li><strong>Question - </strong>" . Html::encode($question->post_title) . "</li>";
                    unset($itemValue['question']);
                }
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Comment ". (++$i) . " - </strong>".  Html::encode($subItem->post_text) ."</li>";
                }
                return $html;
                break;
            case 'MessageEntry':
                $i = 0;
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Response ". (++$i) . " - </strong>".  Html::encode($subItem->{self::$contextParam[$itemKey]}) ."</li>";
                }
                return $html;
                break;
        }

    }

    public static function getBody($itemValue, $itemKey)
    {
        if(!empty($itemValue) && !empty($itemKey)) {
            if ($itemKey != "Answer") {
                return Html::encode($itemValue->{Evidence::$contextParam[$itemKey]});
            } else {
                return Html::encode($itemValue->post_text);
            }
        }
    }

    public static function getFileAPSTS()
    {
        require_once dirname(__DIR__). DIRECTORY_SEPARATOR . "lib/PHPExcel/Classes/PHPExcel.php";
        $profile = Profile::find()->andWhere(["user_id" => Yii::$app->user->id])->one();
        $reg = ManageRegistration::find()->andWhere(['name' => $profile->teacher_type, 'type' => ManageRegistration::TYPE_TEACHER_TYPE])->one();
        $other = ManageRegistration::find()->andFilterWhere(['name' => 'othersFildn'])->one();
        $file_name = null;

        if(!empty($reg) && $reg->default == ManageRegistration::DEFAULT_ADDED && !empty($reg->file_name)) { // if e
            $file_name = $reg->file_name;
        }

        if(!empty($other) && !empty($other->file_name) && is_null($file_name)) {
            $file_name = $other->file_name;
        }


        if(empty($file_name)) {
            return [];
        }
        $path = Yii::getAlias("@webroot") . "/uploads/file/". $file_name;
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        unset($sheetData[1]);
        if(empty($sheetData)) {
            return [];
        }
        return $sheetData;
    }

    public static function getOneAPSTS($id_apsts)
    {
        $data = self::getFileAPSTS();
        $title = "";
        $descr = "";
        foreach ($data as $item) {
            if(isset($item['A']) && $item['A'] == $id_apsts) {
                if(!isset($item['C'])) {
                    $title = !empty($item['A'])?$item['A']:'';
                    $descr = !empty($item['B'])?$item['B']:'';
                } else {
                    $title = !empty($item['B'])?$item['B']:'';
                    $descr = !empty($item['C'])?$item['C']:'';
                }
            }
        }

        return [
            'title' => $title,
            'descr' => $descr,
        ];
    }
}

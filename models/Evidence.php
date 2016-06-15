<?php

class Evidence extends CComponent {
    private static $data;
    private static $_instance;
    private static $wordText;
    private static $docx;

    public static $acitvityType = [
        'ActivitySpaceCreated' => 'Mentorship Circle Post',
        'Question' => 'Community post',
        'Answer' => 'Community response',
        'MessageEntry' => 'Message',
    ];

    public static $relationObject = [
        'ActivitySpaceCreated' => 'Activity',
        'Question' => 'Question',
        'Answer' => 'Answer',
        'MessageEntry' => 'MessageEntry',
    ];

    public static $relationPreview = [
        'ActivitySpaceCreated' => 'Post',
        'Question' => 'Question',
        'Answer' => 'Answer',
        'MessageEntry' => 'MessageEntry',
    ];

    public static $contextMess = [
        'ActivitySpaceCreated' => '(the 2 messages either side of post in circle)',
        'Question' => '(top 5 answers)',
        'Answer' => '(the question and up to 4 comments)',
        'MessageEntry' => '(last 5 message responses)',
    ];

    public static $iconObject = [
        'ActivitySpaceCreated' => '<i class="fa fa-dot-circle-o fa-margin-right"></i>',
        'Question' => '<i class="fa fa-stack-exchange fa-margin-right"></i>',
        'Answer' => '<i class="fa fa-stack-exchange fa-margin-right"></i>',
        'MessageEntry' => '<i class="fa fa-comment fa-margin-right"></i>',
    ];

    public static $contextParam = [
        'ActivitySpaceCreated' => 'message',
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
            $from = str_replace("/" , "-" , trim(explode( "-", $_POST['daterange'])[0]));
            $to = str_replace("/" , "-" , trim(explode( "-", $_POST['daterange'])[1]));
            $period = " AND created_at >= '$from' AND created_at <= '$to'";
        }
        $sql = 'SELECT * 
                FROM content 
                WHERE 
                    object_model != "Post" 
                      AND 
                    object_model != "WBSChat"
                      AND
                    object_model != "Comment"
                      AND
                    created_by =' . Yii::app()->user->id
                    .$period;
        self::$data = Yii::app()->db->createCommand($sql)->queryAll();
        return $this;
    }

    public function filterActivity()
    {
        foreach (self::$data as $key => $value) {
            if($value['object_model'] == "Activity" || $value['object_model'] == "Post") {
                $activity = Activity::model()->find('id=' . $value['object_id']);
				if(isset($activity) && $activity->type != "PostCreated") {
                    unset(self::$data[$key]);
                } else {
                    self::$data[$key]['object_model'] = "ActivitySpaceCreated";
                }

                if($activity->type == "ChatMessage" && $activity->type = "KnowledgeCommentCreated") {
                    unset(self::$data[$key]);
                }
            }
        }

        return $this;
    }

    public function addEntryMessageActivity()
    {
        $period= '';
        if(isset($_POST['daterange'])) {
            $from = str_replace("/" , "-" , trim(explode( "-", $_POST['daterange'])[0]));
            $to = str_replace("/" , "-" , trim(explode( "-", $_POST['daterange'])[1]));
            $period = " AND created_at >= '$from' AND created_at <= '$to'";
        }
        $sql = 'SELECT * 
                FROM message_entry 
                WHERE  
                    created_by =' . Yii::app()->user->id
            .$period;
        $dataMessages = Yii::app()->db->createCommand($sql)->queryAll();

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
            case 'Activity':
                $idPost = $switch::model()->find('id=' . $object['object_id'])->object_id;
                return Post::model()->find('id='. $idPost)->message;
                break;
            case 'Question':
                return $switch::model()->find('id=' . $object['object_id'])->post_text;
                break;
            case 'Answer':
                return $switch::model()->find('id=' . $object['object_id'])->post_text;
                break;
            case 'MessageEntry':
                return $switch::model()->find('id=' . $object['id'])->content;
                break;
        }
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
                case 'ActivitySpaceCreated': // model is Post in db
                    $content = Content::model()->find('id=' . $objectValue);
                    $activity = Activity::model()->find('id=' . $content->object_id);
                    $mainObject = Post::model()->find('id='.$activity->object_id);
                    $lastContentPosts = Content::model()->findAll('object_id >=' . ($mainObject->id - 2) . ' AND object_id<='. ($mainObject->id + 2) . ' AND object_id!='. ($mainObject->id) .' AND object_model = "Post" AND space_id='. $content->space_id);
                    $result = !empty($lastContentPosts)?implode(",", CHtml::listData($lastContentPosts, 'object_id', 'object_id')):0;
                    $subObject = Post::model()->findAll('id IN (' . $result . ')');
                    $subData[] = [
                        $objectKey => [
                            'id' => $mainObject->id,
                            'title' => $mainObject->message,
                            'context' => $subObject,
                        ]
                    ];
                    break;
                case 'Question':
                    $content = Content::model()->find('id=' . $objectValue);
                    $mainObject = Question::model()->find('id=' . $content->object_id);
                    $subObject = Answer::model()->findAll('question_id = ' . $mainObject->id . ' AND post_type = "answer" ORDER BY created_at DESC LIMIT 5');
                    $subData[] = [
                        $objectKey => [
                            'id' => $mainObject->id,
                            'title' => $mainObject->post_text,
                            'context' => $subObject,
                        ]
                    ];
                    break;
                case 'Answer':
                    $content = Content::model()->find('id=' . $objectValue);
                    $mainObject = $objectKey::model()->find('id=' . $content->object_id);
                    $questionObject = Answer::model()->find('id=' . $mainObject->question_id);
                    $subObject = Answer::model()->findAll('parent_id = ' . $mainObject->id . ' AND post_type = "comment" ORDER BY created_at DESC LIMIT 5');
                    $subData[] = [
                        $objectKey => [
                            'id' => $mainObject->id,
                            'title' => $mainObject->post_text,
                            'context' => array_merge([$questionObject], $subObject),
                        ]
                    ];
                    break;
                case 'MessageEntry':
                    $mainObject = $objectKey::model()->find('id=' . $objectValue);
                    $preCount = 5;
                    $subObject = MessageEntry::model()->findAll('1=1 ORDER BY created_at DESC LIMIT 5');
                    $subData[] = [
                        $objectKey => [
                            'id' => $mainObject->id,
                            'title' => $mainObject->content,
                            'context' => $subObject,
                        ]
                    ];
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
                $answer = $switch::model()->find('id=' . $object['object_id']);
                if(!empty($answer)) {
                    $question = $switch::model()->find('id=' . $answer->question_id);
                    return User::model()->find('id=' . $question->created_by)->username;
                }
                return "-";
                break;
            case 'MessageEntry':
                $groupMessages = CHtml::listData(UserMessage::model()->findAll('user_id !='.Yii::app()->user->id. ' AND message_id=' . $object['message_id']),"user_id", "user_id");
                $users = User::model()->findAll('id IN (' . implode(",", $groupMessages) . ')');
                if(!empty($users)) {
                    $usernames = implode("<br />" , CHtml::listData($users, "username", "username"));
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
        $path = Yii::getPathOfAlias('webroot') . '/uploads/file/evidenceDoc';
        self::$docx->modifyPageLayout('A3');
        self::$docx->modifyPageLayout('A3');
        self::$docx->createDocx($path);
        $absolutePath = Yii::app()->request->hostInfo . "/". Yii::app()->request->baseUrl . "/uploads/file/evidenceDoc.docx";
        return $absolutePath;
    }

    public function prepareHtmlToHtml($html)
    {
        require_once dirname(__DIR__). DIRECTORY_SEPARATOR . "lib/phpdocx/classes/CreateDocx.inc";

        self::$docx = new CreateDocx();
        self::$docx->setDefaultFont('Arial');
        $options = array(
            'src' => Yii::getPathOfAlias("webroot").'/themes/humhub-themes-tq/img/teachconnect-logo-header.png',
            'imageAlign' => 'left',
            'scaling' => 25,
            'spacingTop' => 10,
            'spacingBottom' => 0,
            'spacingLeft' => 0,
            'spacingRight' => 20,
            'textWrap' => 4,
            'borderWidth' => 0,
        );
        self::$docx->addImage($options);
        self::$docx->addText("Evidence Export", [
            'wordWrap' => true,
            'textAlign' => 'right',
        ]);
        self::$docx->addText("2016-05-12", [
            'wordWrap' => true,
            'textAlign' => 'right',
        ]);

        self::$docx->embedHTML($html);

        $options = array(
            'src' => Yii::getPathOfAlias("webroot").'/themes/humhub-themes-tq/img/teachconnect-logo-footer.png',
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

    public static function responseData($itemContext, $itemKeyContext)
    {
        $j=0;
        $result = "";
        switch($itemKeyContext) {
            case 'ActivitySpaceCreated': // model is Post in db
                $i=1;
                foreach ($itemContext as $context) {
                    $firstname = User::model()->find("id=". $context->created_by)->username;
                    $preWord = ($i==3)?"Previous Message":"Following Message";
                    ($i==3)?$i=1:'';
                    $result.="<tr>";
                        $result.="<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                        $result.="<td> <strong>" . $preWord ." ". $i . " (" . $firstname .")-</strong> ". $context->{Evidence::$contextParam[$itemKeyContext]} ."</td>";
                    $result.="</tr>";
                    $i++;
                }
                return $result;
                break;
            case 'Question':
                foreach ($itemContext as $context) {
                    $result.="<tr>";
                        $result.="<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                        $result.="<td> <strong>Answer " . (++$j) . "-</strong> ". ($context->{Evidence::$contextParam[$itemKeyContext]}) ."</td>";
                    $result.="</tr>";
                }
                return $result;
                break;
            case 'Answer':
                $i = 0;
                foreach ($itemContext as $context) {

                    if($i == 0) {
                        $text = $context->{Evidence::$contextParam[$itemKeyContext]};
                        $preWord = "Question";
                        $type = "checkbox_question";
                    } else {
                        $text = $context->post_text;
                        $preWord = "Comment " . $i;
                        $type = "checkbox";
                    }
                    $result.="<tr>";
                        $result.="<td class='text-center'><input class='itemSelect context-checkbox' data-type='".$type."' data-id='$context->id' type='checkbox'></td>";
                        $result.="<td><strong>". $preWord  . " - </strong> ". $text ."</td>";
                    $result.="</tr>";
                    $i++;
                }
                return $result;
                break;
            case 'MessageEntry':
                foreach ($itemContext as $context) {
                    $result.="<tr>";
                        $result.="<td class='text-center'><input class='itemSelect context-checkbox' data-type='checkbox' data-id='$context->id' type='checkbox'></td>";
                        $result.="<td> <strong>Response " . (++$j) . "-</strong> ". $context->{Evidence::$contextParam[$itemKeyContext]} ."</td>";
                    $result.="</tr>";
                }
                return $result;
                break;
        }
    }

    public static function getPreparePreivew($data)
    {
        $listActivity = $data;
        $objects = [];

        foreach ($listActivity as $TypeAndId => $arrayData) {
            $objects = array_merge(self::getObjectPrepareData($TypeAndId, $arrayData), $objects);
        }

        return $objects;
    }

    private static function getObjectPrepareData($TypeAndId, $arrayData) {
        $subData = [];
        $mainKey = explode("_", $TypeAndId)[0];
        $mainId = explode("_", $TypeAndId)[1];
            switch ($mainKey) {
                case 'ActivitySpaceCreated': // model is Post in db
                    $nameRelation = self::$relationPreview[$mainKey];

                    $mainObject = $nameRelation::model()->find("id=".$mainId);
                    $note = $arrayData['textarea'];
                    $apsts = $arrayData['select'];
                    $subObject = $nameRelation::model()->findAll('id IN(' . implode(",", $arrayData['checkbox']) . ')');

                    $subData[$mainKey] = [
                            'mainObject' => $mainObject,
                            'note' => $note,
                            'apsts' => $apsts,
                            'subObject' => $subObject,
                    ];
                    break;
                case 'Question':
                    $nameRelation = self::$relationPreview[$mainKey];

                    $mainObject = $nameRelation::model()->find("id=".$mainId);
                    $note = $arrayData['textarea'];
                    $apsts = $arrayData['select'];

                    $subObject = Answer::model()->findAll('id IN(' . implode(",", $arrayData['checkbox']) . ')');
                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                    break;
                case 'Answer':
                    $nameRelation = self::$relationPreview[$mainKey];
                    $mainObject = $nameRelation::model()->find("id=".$mainId);
                    $note = $arrayData['textarea'];
                    $apsts = $arrayData['select'];
                    $subObject = $nameRelation::model()->findAll('id IN(' . implode(",", $arrayData['checkbox']) . ')');

                    $subQuestion = NULL;
                    if(!empty($arrayData['checkbox_question'])) {
                        $subObject['question'] = $nameRelation::model()->find('id='.$arrayData['checkbox_question'][0]);
                    }

                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                    break;
                case 'MessageEntry':
                    $nameRelation = self::$relationPreview[$mainKey];

                    $mainObject = $nameRelation::model()->find("id=".$mainId);
                    $note = $arrayData['textarea'];
                    $apsts = $arrayData['select'];
                    $subObject = $nameRelation::model()->findAll('id IN(' . implode(",", $arrayData['checkbox']) . ')');

                    $subData[$mainKey] = [
                        'mainObject' => $mainObject,
                        'note' => $note,
                        'apsts' => $apsts,
                        'subObject' => $subObject,
                    ];
                    break;
        }

        return $subData;
    }

    public static function getPreviewUlHtml($itemValue, $itemKey) {
        $html= "";
        switch($itemKey) {
            case 'ActivitySpaceCreated': // model is Post in db
                $i=1;
                foreach ($itemValue as $subItem) {
                    $firstname = User::model()->find("id=". $subItem->created_by)->username;
                    $preWord = ($i==3)?"Previous Message":"Following Message";
                    ($i==3)?$i=1:'';
                    $html.="<li><strong>Response ". ($i) . " - </strong>".  $subItem->{self::$contextParam[$itemKey]} ."</li>";
                    $i++;
                }
                return $html;
                break;
            case 'Question':
                $i=0;
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Answer ". (++$i) . " - </strong>".  $subItem->{self::$contextParam[$itemKey]} ."</li>";
                }
                return $html;
                break;
            case 'Answer':
                $i = 0;
                if(isset($itemValue['question'])) {
                    $question = $itemValue['question'];
                    $html.="<li><strong>Question - </strong>".  $question->post_title ."</li>";
                }
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Comment ". (++$i) . " - </strong>".  $subItem->post_text ."</li>";
                }
                return $html;
                break;
            case 'MessageEntry':
                $i = 0;
                foreach ($itemValue as $subItem) {
                    $html.="<li><strong>Response ". (++$i) . " - </strong>".  $subItem->{self::$contextParam[$itemKey]} ."</li>";
                }
                return $html;
                break;
        }

    }

    public static function getBody($itemValue, $itemKey)
    {
        if($itemKey != "Answer") {
            return  $itemValue->{Evidence::$contextParam[$itemKey]};
        } else {
            return $itemValue->post_text;
        }
    }



    public static function getFileAPSTS() {

        require_once dirname(__DIR__). DIRECTORY_SEPARATOR . "lib/PHPExcel/Classes/PHPExcel.php";
        $profile = Profile::model()->find("user_id=". Yii::app()->user->id);
        $reg = ManageRegistration::model()->find('name="' .$profile->teacher_type. '"');

        $file_name = $reg->file_name;
        $path = Yii::getPathOfAlias("webroot") . "/uploads/file/". $file_name;
        $objPHPExcel = PHPExcel_IOFactory::load($path);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
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
            if($item['A'] == $id_apsts) {
                $title = $item['B'];
                $descr = $item['C'];
            }
        }
        return [
            'title' => $title,
            'descr' => $descr,
        ];
    }
}
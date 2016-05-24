<?php

class Evidence extends CComponent {
    private static $data;
    private static $_instance;
    private static $wordText;
    private static $docx;

    public static $acitvityType = [
        'ActivitySpaceCreated' => 'Mentorship Circle',
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

                if($activity->type == "ChatMessage") {
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

        $this->sksort(self::$data, "created_at");
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
        $html = '';
        foreach ($listActivity as $objectActivityKey => $objectActivityValue) {
            $html .= self::getObjectHtml($objectActivityKey, $objectActivityValue);
        }

        return $html;
    }

    public static function getObjectHtml($objectKey, $objectValues)
    {
        $subHtml = '';
        foreach ($objectValues as $objectValue) {
            switch ($objectKey) {
                case 'ActivitySpaceCreated': // model is Post in db
                    $mainObject = Post::model()->find('id=' . $objectValue);
                    $lastContentPosts = Content::model()->findAll('space_id = ' . $mainObject->id . ' AND object_model = "Post" ORDER BY created_at DESC LIMIT 5');
                    $subObject = Post::model()->findAll('id IN (' . implode(",", CHtml::listData($lastContentPosts, "object_id", "object_id")) . ')');
                    $subHtml.= self::getHtml($object, $mainObject, $subObject);
                    break;
                case 'Question':
                    $mainObject = $objectKey::model()->find('id=' . $objectValue);
                    $subObject = Answer::model()->findAll('question_id = ' . $mainObject->id . ' AND post_type = "answer" ORDER BY created_at DESC LIMIT 5');
                    $subHtml.= self::getHtml($mainObject, $mainObject, $subObject);
                    break;
                case 'Answer':
                    $mainObject = $objectKey::model()->find('id=' . $objectValue);
                    $subObject = Answer::model()->findAll('parent_id = ' . $mainObject->id . ' AND post_type = "comment" ORDER BY created_at DESC LIMIT 5');
                    $subHtml.= self::getHtml($mainObject, $mainObject, $subObject);
                    break;
                case 'MessageEntry':
                    $mainObject = $objectKey::model()->find('id=' . $object->id . ' AND message_id=' . $object->message_id);
                    $preCount = 5;
                    $subObject = null;
                    if ($mainObject && $mainObject->id > $preCount) {
                        $subObject = MessageEntry::model()->findAll('id between ' . ($mainObject->id - $preCount - 1) . ' AND ' . ($mainObject->id - 1) . ' AND message_id = ' . $object->message_id . ' ORDER BY created_at DESC');
                    } else {
                        $subObject = MessageEntry::model()->findAll('1=1 ORDER BY created_at DESC LIMIT 5');
                    }
                    $subHtml.= self::getHtml($object, $mainObject, $subObject);
                    break;
            }
        }

        return $subHtml;
    }

    private static function getHtml($object, $mainObject, $subObject)
    {
        $switch = self::$relationObject[$object->object_model];
        switch($switch) {
            case 'Space':
                $itemsHtml = '';
                foreach ($subObject as $subItem) {
                    $itemsHtml .= "<li>" . $subItem->message . "</li>";
                }
                $html = " <div class='block-item'>
                            <input type='checkbox' class='check-item' checked>
                            <div class='content-item'>
                                <h1 style='text-align:center'><span>Space: </span>". $mainObject->name ."</h1>
                                <span>Last 5 posts</span>
                                <ul>
                                    ". $itemsHtml ."
                                </ul>
                            </div>
                          </div>";
                return $html;
                break;
                break;
            case 'Question':
                $itemsHtml = '';
                foreach ($subObject as $subItem) {
                    $itemsHtml .= "<li>" . $subItem->post_text . "</li>";
                }
                $html = " <div class='block-item'>
                            <input type='checkbox' class='check-item' checked>
                            <div class='content-item'>
                                <h1><span>Post: </span>". $mainObject->post_text ."</h1>
                                <span>Last 5 responses</span>
                                <ul>
                                    ". $itemsHtml ."
                                </ul>
                            </div>
                          </div>";
                return $html;
                break;
            case 'Answer':
                $itemsHtml = '';
                foreach ($subObject as $subItem) {
                    $itemsHtml .= "<li>" . $subItem->post_text . "</li>";
                }
                $questionObject = Question::model()->find('id = ' . $mainObject->question_id);
                $html = " <div class='block-item'>
                            <input type='checkbox' class='check-item' checked>
                            <div class='content-item'>
                                <h1><span>Post: </span>". $questionObject->post_text ."</h1>
                                <p><span>Response: </span>". $mainObject->post_text ."<p>
                                <span>Last 5 comments</span>
                                <ul>
                                    ". $itemsHtml ."
                                </ul>
                            </div>
                          </div>";
                return $html;
                break;
            case 'MessageEntry':
                $itemsHtml = '';
                foreach ($subObject as $subItem) {
                    $itemsHtml .= "<li>" . $subItem->text . "</li>";
                }
                $html = " <div class='block-item'>
                            <input type='checkbox' class='check-item' checked>
                            <div class='content-item'>
                                <h1><span>Message: </span>". $mainObject->content ."</h1>
                                <span>Last 5 responses</span>
                                <ul>
                                    ". $itemsHtml ."
                                </ul>
                            </div>
                          </div>";
                return $html;
                break;
        }
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
        self::$docx->createDocx('simpleHTML');
    }

    public function prepareHtmlToHtml($html)
    {
        require_once dirname(__DIR__). DIRECTORY_SEPARATOR . "lib/phpdocx/classes/CreateDocx.inc";

        self::$docx = new CreateDocx();
        self::$docx->embedHTML($html);
        return $this;
    }
}
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
        'WBSChat' => 'Message',
    ];

    public static $relationObject = [
        'ActivitySpaceCreated' => 'Space',
        'Question' => 'Question',
        'Answer' => 'Answer',
        'WBSChat' => 'WBSChat',
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
                    created_by =' . Yii::app()->user->id
                    .$period;
        self::$data = Yii::app()->db->createCommand($sql)->queryAll();
        array_multisort(self::$data, SORT_DESC);
        return $this;
    }

    public function filterActivity()
    {
        foreach (self::$data as $key => $value) {
            if($value['object_model'] == "Activity" || $value['object_model'] == "Post") {
                $activity = Activity::model()->find('id=' . $value['object_id']);
				if(isset($activity) && $activity->type != "ActivitySpaceCreated") {
                    unset(self::$data[$key]);
                } else {
                    self::$data[$key]['object_model'] = "ActivitySpaceCreated";
                }
            }
        }

        return $this;
    }

    public function getData() {
        return self::$data;
    }

    public static function getText($object)
    {
        $switch = self::$relationObject[$object['object_model']];
        switch($switch) {
            case 'Space':
                return $switch::model()->find('id=' . $object['space_id'])->description;
                break;
            case 'Question':
                return $switch::model()->find('id=' . $object['object_id'])->post_text;
                break;
            case 'Answer':
                return $switch::model()->find('id=' . $object['object_id'])->post_text;
                break;
            case 'WBSChat':
                return $switch::model()->find('id=' . $object['object_id'])->text;
                break;
        }
    }

    public static function getPrepareObjects($data)
    {
        $splitData = implode(',', $data);
        $listActivity = Content::model()->findAll('id IN (' . $splitData . ')');
        $html = '';
        foreach ($listActivity as $objectActivity) {
            $html .= self::getObjectHtml($objectActivity);
        }

        return $html;
    }

    public static function getObjectHtml($object)
    {
        if($object->object_model == "Activity") {
            $activityObject = Activity::model()->find('id=' . $object->object_id);
            if(!empty($activityObject) && $activityObject->type == "ActivitySpaceCreated") {
                $object->object_model = "ActivitySpaceCreated";
            }
        }
        $switch = self::$relationObject[$object->object_model];
        switch($switch) {
            case 'Space':
                $mainObject = $switch::model()->find('id=' . $object->space_id);
                $lastContentPosts = Content::model()->findAll('space_id = '. $mainObject->id . ' AND object_model = "Post" ORDER BY created_at DESC LIMIT 5');
                $subObject = Post::model()->findAll('id IN (' . implode(",", CHtml::listData($lastContentPosts, "object_id", "object_id")) . ')');
                return self::getHtml($object, $mainObject, $subObject);
                break;
            case 'Question':
                $mainObject = $switch::model()->find('id=' . $object->object_id);
                $subObject = Answer::model()->findAll('question_id = '. $mainObject->id . ' AND post_type = "answer" ORDER BY created_at DESC LIMIT 5');
                return self::getHtml($object, $mainObject, $subObject);
                break;
            case 'Answer':
                $mainObject = $switch::model()->find('id=' . $object->object_id);
                $subObject = Answer::model()->findAll('parent_id = '. $mainObject->id . ' AND post_type = "comment" ORDER BY created_at DESC LIMIT 5');
                return self::getHtml($object, $mainObject, $subObject);
                break;
            case 'WBSChat':
                $mainObject = $switch::model()->find('id=' . $object->object_id);
                $preCount = 5;
                $subObject = null;
                if($mainObject && $mainObject->id > $preCount) {
                    $subObject = WBSChat::model()->findAll('id between '. ($mainObject->id-$preCount-1) . ' AND ' . ($mainObject->id-1) . ' ORDER BY created_at DESC');
                } else {
                    $subObject = WBSChat::model()->findAll('1=1 ORDER BY created_at DESC LIMIT 5');
                }
                return self::getHtml($object, $mainObject, $subObject);
                break;
        }
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
            case 'WBSChat':
                $itemsHtml = '';
                foreach ($subObject as $subItem) {
                    $itemsHtml .= "<li>" . $subItem->text . "</li>";
                }
                $html = " <div class='block-item'>
                            <input type='checkbox' class='check-item' checked>
                            <div class='content-item'>
                                <h1><span>Message: </span>". $mainObject->text ."</h1>
                                <span>Last 5 messages</span>
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
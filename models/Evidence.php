<?php

class Evidence extends CComponent {
    private static $data;
    private static $_instance;
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
        if(isset($_POST['dateFrom']) && isset($_POST['dateTo'])) {
            $from = str_replace("." , "-" , $_POST['dateFrom']);
            $to = str_replace("." , "-" , $_POST['dateTo']);
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
}
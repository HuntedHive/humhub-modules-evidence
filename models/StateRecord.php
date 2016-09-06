<?php


namespace humhub\modules\evidence\models;

use Yii;
use yii\base\Object;
use yii\db\ActiveRecord;

class StateRecord extends ActiveRecord
{

    const TEACHER_TYPE = "teacher_type_";

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'state_record';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            [['key', 'created_at'], 'required'],
            [['key'], 'string'],
            [['created_at', 'created_by'], 'integer'],
        );
    }

    public static function stateTeacheType()
    {
        $user_id = Yii::$app->user->id;
        $object = self::find()->andFilterWhere(['key' => self::TEACHER_TYPE . $user_id, 'created_by' => $user_id])->one();
        if(empty($object)) {
            return true;
        } else {
            $timestamp = time();
            if( $object->created_at - $timestamp < 0 ) {
                return true;
            } else {
                setcookie(self::TEACHER_TYPE . $user_id, 1, $object->created_at, "/");
                return false;
            }
        }
    }

    public static function saveStateTeacherType()
    {
        $user_id = Yii::$app->user->id;
        $year = time()+ (3600 * 24 * 30 * 12); // where 3600 = 1h
        setcookie(self::TEACHER_TYPE . $user_id, 1, $year, "/");
        self::saveState(self::TEACHER_TYPE . $user_id, $year);
    }

    private static function saveState($key, $timestamp)
    {

        $model = self::find()->andFilterWhere(['key' => $key, 'created_by' => Yii::$app->user->id])->one();
        if(empty($model)) { // create new state

            $object = new self();
            $object->key = $key;
            $object->created_at = $timestamp;
            $object->save();
        } else { // update time
            $model->created_at = $timestamp;
            $model->save();
        }
    }

    public function beforeSave($insert)
    {
        $this->created_by = Yii::$app->user->id;
        return parent::beforeSave($insert);
    }

}

<?php

class CurrStepEvidence extends HActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'current_step_evidence';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            [['step1','step2','step3'], 'safe'],
        );
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            //'module_id' => 'Module',
        );
    }

    public static function loadHtmlCookie()
    {
        if(isset($_COOKIE['LoadExport'])) {
            $user_id = Yii::app()->user->id;
            $data = CurrStepEvidence::model()->find('created_by=' . $user_id);
            return $data;
        } else {
            return null;
        }
    }

    public static function setCurrentStep($data, $step)
    {
        $user_id = Yii::app()->user->id;
        $model = self::model()->find('created_by='.$user_id);

        if(!empty($model)) {
            self::model()->updateByPk($model->id, [ $step=> $data]);
        } else {
            $self = new self();
            $self->$step = $data;
            $self->save();
        }
    }
}

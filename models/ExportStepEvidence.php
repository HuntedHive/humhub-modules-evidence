<?php

class ExportStepEvidence extends HActiveRecord
{

    const STEP1 = 'step1';
    const STEP2 = 'step2';
    const STEP3 = 'step3';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'save_steps_evidence';
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
            ['name', 'unique'],
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

    public static function getListNames()
    {
        return self::model()->findAll('created_by='.Yii::app()->user->id);
    }

    public static function saveExport($exportName)
    {
        $currentStep = CurrStepEvidence::model()->find('created_by='.Yii::app()->user->id);
//        var_dump($currentStep);die;
        $exportStep = new self();
        $exportStep->name = $exportName;
        $exportStep->step1 = $currentStep->step1;
        $exportStep->step2 = $currentStep->step2;

        $exportStep->obj_step1 = $currentStep->obj_step1;
        $exportStep->obj_step2 = $currentStep->obj_step2;
        $exportStep->save();
    }
}

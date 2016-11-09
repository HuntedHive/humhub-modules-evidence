<?php

namespace humhub\modules\evidence\models;

use Yii;
use humhub\components\ActiveRecord;

class ExportStepEvidence extends ActiveRecord
{

    const STEP1 = 'step1';
    const STEP2 = 'step2';
    const STEP3 = 'step3';

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'save_steps_evidence';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['name', 'required'],
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
        return self::find()->andWhere(['created_by' => Yii::$app->user->id])->all();
    }

    public static function saveExport($exportName)
    {
        $currentStep = CurrStepEvidence::find()->andWhere(['created_by' => Yii::$app->user->id])->one();
        $exportStep = new self();
        $exportStep->name = $exportName;
        $exportStep->step1 = $currentStep->step1;
        $exportStep->step2 = $currentStep->step2;

        $exportStep->obj_step1 = $currentStep->obj_step1;
        $exportStep->obj_step2 = $currentStep->obj_step2;
        $exportStep->save();
    }
}

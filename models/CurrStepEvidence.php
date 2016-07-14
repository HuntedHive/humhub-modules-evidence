<?php

namespace humhub\modules\evidence\models;

use Yii;
use humhub\components\ActiveRecord;

class CurrStepEvidence extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'current_step_evidence';
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
            $user_id = Yii::$app->user->id;
            $data = CurrStepEvidence::find()->andWhere(['created_by' => $user_id])->one();
            if (empty($data))
                return '';
            
            return $data;
        }
    }

    /**
     * @param null $data html
     * @param null $objData json encode data
     * @param $step Current Step
     */
    public static function setCurrentStep($data = null, $objData = null, $step)
    {
        $user_id = Yii::$app->user->id;
        $model = self::find()->andWhere(['created_by' => $user_id])->one();
        $objectStep = "obj_".$step;
        if(!empty($model)) {
            if(!is_null($data)) {
                $model->$step = $data;
            }

            if(!is_null($objData)) {
                $model->$objectStep = $objData;
            }
            $model->save();
        } else {
            $self = new self();
            if(!is_null($data)) {
                $self->$step = $data;
            }

            if(!is_null($objData)) {
                $self->$objectStep = $objData;
            }
            $self->save();
        }
    }
    
    public static function getDataFromLoadExport($exportData)
    {
        $currentStep = CurrStepEvidence::find()->andWhere(['created_by' => Yii::$app->user->id])->one();
        $currentStep->step1 = $exportData->step1;
        $currentStep->step2 = $exportData->step2;

        $currentStep->obj_step1 = $exportData->obj_step1;
        $currentStep->obj_step2 = $exportData->obj_step2;
        $currentStep->save();
    }
}

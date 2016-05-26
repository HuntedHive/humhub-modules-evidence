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
}

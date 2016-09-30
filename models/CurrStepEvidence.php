<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016  Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 *
 */

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
            $user_id = Yii::app()->user->id;
            $data = CurrStepEvidence::model()->find('created_by=' . $user_id);
            if(empty($data))
                return '';

            return $data;
    }

    /**
     * @param null $data html
     * @param null $objData json encode data
     * @param $step Current Step
     */
    public static function setCurrentStep($data = null, $objData = null, $step)
    {
        $user_id = Yii::app()->user->id;
        $model = self::model()->find('created_by='.$user_id);
//        var_dump($model);die;
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
        $currentStep = CurrStepEvidence::model()->find('created_by='.Yii::app()->user->id);
        $currentStep->step1 = $exportData->step1;
        $currentStep->step2 = $exportData->step2;

        $currentStep->obj_step1 = $exportData->obj_step1;
        $currentStep->obj_step2 = $exportData->obj_step2;
        $currentStep->save();
    }
}

<?php

namespace humhub\modules\evidence\controllers;

use humhub\modules\evidence\models\Evidence;
use humhub\modules\evidence\models\CurrStepEvidence;
use humhub\modules\evidence\models\ExportStepEvidence;
use humhub\modules\evidence\models\StateRecord;
use Yii;
use yii\base\Component;
use yii\base\Object;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use humhub\components\Controller;

class EvidenceController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('prepare', 'sectionPrepareWord', 'saveToWord', 'saveExport', 'loadExport', 'sectionPreview', 'save-current-html', 'deleteExport'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionSaveToWord()
    {
        $evidence = new Evidence;
        $body = $_POST['table'];
        $path = $evidence->prepareHtmlToHtml($body)->saveWord();
        echo json_encode(['flag' => 1, 'path' => $path]);
    }

    /**
     * First step
     */
    public function actionPrepare()
    {
        $rawData = Evidence::instance()->getAllQuery()->filterActivity()->addEntryMessageActivity()->getData();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $rawData,
            'sort'=>array(
                'attributes'=>array(
                    "created_at" => [
                        'desc' => 'created_at desc'
                    ]
                ),
            ),
        ]);

        return $this->render('index', array(
            'dataProvider'=>$dataProvider,
            'step' => ExportStepEvidence::STEP1,
            'stepUrl' => Url::toRoute("/evidence/evidence/section-prepare-word"),
        ));
    }

    /**
     * Second step
     */
    public function actionSectionPrepareWord()
    {
        if((!empty($_POST) && isset($_POST['activityItems']) || true)) {
            $data = CurrStepEvidence::loadHtmlCookie();
            $itemsList = isset($_POST['activityItems'])?$_POST['activityItems']:json_decode($data->obj_step1, true);
            CurrStepEvidence::setCurrentStep(null, json_encode($itemsList), ExportStepEvidence::STEP1);
            if(empty($itemsList)) {
                return $this->redirect(Url::toRoute("/evidence/evidence/prepare"));
            }
            $dataObjects = Evidence::getPrepareObjects($itemsList);
            return $this->render("displayContext", [
                'dataObjects' => array_filter($dataObjects),
                'step' => ExportStepEvidence::STEP2,
                'stepUrl' => Url::toRoute("/evidence/evidence/section-preview"),
                'previousUrl' => Url::toRoute("/evidence/evidence/prepare"),
            ]);
        } else {
            return $this->redirect(Url::toRoute("/evidence/evidence/prepare"));
        }
    }

    /**
     * Third step
     */
    public function actionSectionPreview()
    {
        if((!empty($_POST) && isset($_POST['activityItems'])) || true) {
            $data = CurrStepEvidence::loadHtmlCookie();
            $itemsList = isset($_POST['activityItems'])?$_POST['activityItems']:json_decode($data->obj_step2, true);
            CurrStepEvidence::setCurrentStep(null, json_encode($itemsList), ExportStepEvidence::STEP2);
            if(empty($itemsList)) {
                return $this->redirect(Url::toRoute("/evidence/evidence/prepare"));
            }

            $dataObjects = Evidence::getPreparePreivew($itemsList);
            return $this->render("preview", [
                'dataObjects' => $dataObjects,
                'step' => ExportStepEvidence::STEP1,
                'previousUrl' => Url::toRoute("/evidence/evidence/section-prepare-word"),
            ]);
        } else {
            return $this->redirect(Url::toRoute("/evidence/evidence/prepare"));
        }
    }

    public function actionSaveExport()
    {
        $this->forcePostRequest();

        $output = [];
        parse_str($_POST['exportData'], $output); // here $step and $saveExport
        parse_str($_POST['obj_data'], $dataObj);

        $currentStep = $output['step'];
        $exportName = $output['saveExport'];
        $exportData = $_POST['html'];
        if (!isset($dataObj['activityItems'])) {
            ExportStepEvidence::saveExport($exportName);
            echo json_encode(['flag' => true, 'redirect' => Yii::$app->request->referrer]);
        } else {
            $exportObjData = json_encode($dataObj['activityItems']);
            CurrStepEvidence::setCurrentStep($exportData, $exportObjData, $currentStep);
            ExportStepEvidence::saveExport($exportName);
            setcookie("LoadExport", 1, time() + 3600 * 24 * 10, "/");
            echo json_encode(['flag' => true, 'redirect' => Yii::$app->request->referrer]);
        }
    }

    public function actionLoadExport()
    {
        $this->forcePostRequest();

        $exportId = $_POST['exportId'];
        $model = ExportStepEvidence::findOne($exportId);
        if(!empty($model)) {
            CurrStepEvidence::getDataFromLoadExport($model);
            setcookie("LoadExport", 1, time()+3600*24*10, "/");
            echo json_encode(['flag' => true, 'redirect' => Url::toRoute("/evidence/evidence/prepare")]);
            return;
        }

        echo json_encode(['flag' => false, 'error' => 'Export by current name not found']);
    }

    public function actionSaveCurrentHtml()
    {
        $this->forcePostRequest();
        if(isset($_POST['html']) && !empty($_POST['html'])) {
            parse_str($_POST['exportData'], $output); // here $step and $saveExport

            $currentStep = $output['step'];
            $exportData = $_POST['html'];
            CurrStepEvidence::setCurrentStep($exportData, null, $currentStep);
        }
    }

    public function actionDeleteExport()
    {
        $this->forcePostRequest();
        if(isset($_POST['id']) && !empty($_POST)) {
            ExportStepEvidence::findOne($_POST['id'])->delete();
        }
    }

    public function actionCloseState()
    {
        $this->forcePostRequest();

        if(isset($_POST['btn-close'])) {
            StateRecord::saveStateTeacherType();
        }
    }
}

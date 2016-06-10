<?php

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
				'actions'=>array('prepare', 'sectionPrepareWord', 'saveToWord', 'saveExport', 'loadExport', 'sectionPreview', 'saveCurrentHtml'),
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
		$evidence->prepareHtmlToHtml($_POST['table'])->saveWord();
		echo 'success';
	}

	public function actionPrepare()
	{
		Yii::import("application.modules.evidence.models.Evidence");
		$rawData = Evidence::instance()->getAllQuery()->filterActivity()->addEntryMessageActivity()->getData();
		$dataProvider = new CArrayDataProvider($rawData, [
			'sort'=>array(
				'attributes'=>array(
					"created_at" => [
						'desc' => 'created_at desc'
					]
				),
			),
		]);

		$this->render('index', array(
			'dataProvider'=>$dataProvider,
			'step' => ExportStepEvidence::STEP1,
			'stepUrl' => Yii::app()->createUrl("/evidence/evidence/sectionPrepareWord"),
		));
	}

	public function actionSectionPreview()
	{
		User::model()->findByPk(Yii::app()->user->id);
		if((!empty($_POST) && isset($_POST['activityItems'])) || isset($_COOKIE['LoadExport'])) {
			$data = CurrStepEvidence::loadHtmlCookie();
			$itemsList = isset($_POST['activityItems'])?$_POST['activityItems']:json_decode($data->obj_step2, true);
			CurrStepEvidence::setCurrentStep(null, json_encode($itemsList), ExportStepEvidence::STEP2);
			$dataObjects = Evidence::getPreparePreivew($itemsList);
			$this->render("preview", [
				'dataObjects' => $dataObjects,
				'step' => ExportStepEvidence::STEP3,
				'previousUrl' => Yii::app()->createUrl("/evidence/evidence/sectionPrepareWord"),
			]);
		} else {
			return $this->redirect(Yii::app()->createUrl("/evidence/evidence/prepare"));
		}
	}

	public function actionSectionPrepareWord()
	{
		if((!empty($_POST) && isset($_POST['activityItems']) || isset($_COOKIE['LoadExport']))) {
			$data = CurrStepEvidence::loadHtmlCookie();
			$itemsList = isset($_POST['activityItems'])?$_POST['activityItems']:json_decode($data->obj_step1, true);

			CurrStepEvidence::setCurrentStep(null, json_encode($itemsList), ExportStepEvidence::STEP1);
			$dataObjects = Evidence::getPrepareObjects($itemsList);
			$this->render("displayContext", [
				'dataObjects' => $dataObjects,
				'step' => ExportStepEvidence::STEP2,
				'stepUrl' => Yii::app()->createUrl("/evidence/evidence/sectionPreview"),
				'previousUrl' => Yii::app()->createUrl("/evidence/evidence/prepare"),
			]);
		} else {
			return $this->redirect(Yii::app()->createUrl("/evidence/evidence/prepare"));
		}
	}

	public function actionSaveExport()
	{
		$output = [];
		parse_str($_POST['exportData'], $output); // here $step and $saveExport
		parse_str($_POST['obj_data'], $dataObj);

		$currentStep = $output['step'];
		$exportName = $output['saveExport'];
		$exportData = $_POST['html'];
		$exportObjData = json_encode($dataObj['activityItems']);
		CurrStepEvidence::setCurrentStep($exportData, $exportObjData, $currentStep);
		ExportStepEvidence::saveExport($exportName);
		setcookie("LoadExport", 1, time()+3600*24*10, "/");
		echo json_encode(['flag' => true, 'redirect' => Yii::app()->request->urlReferrer]);
	}

	public function actionLoadExport()
	{
		$exportId = $_POST['exportId'][0];
		$model = ExportStepEvidence::model()->find('id='. $exportId);
		if(!empty($model)) {
			CurrStepEvidence::getDataFromLoadExport($model);
			setcookie("LoadExport", 1, time()+3600*24*10, "/");
			echo json_encode(['flag' => true, 'redirect' => Yii::app()->createUrl("/evidence/evidence/prepare")]);
			return;
		}

		echo json_encode(['flag' => false, 'error' => 'Export by current name not found']);
	}

	public function actionSaveCurrentHtml()
	{
		if(isset($_POST['html']) && !empty($_POST['html'])) {
			parse_str($_POST['exportData'], $output); // here $step and $saveExport

			$currentStep = $output['step'];
			$exportData = $_POST['html'];
			CurrStepEvidence::setCurrentStep($exportData, null, $currentStep);
		}
	}
}

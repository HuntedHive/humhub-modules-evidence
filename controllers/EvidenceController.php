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
				'actions'=>array('prepare', 'sectionPrepareWord', 'saveToWord', 'saveExport', 'loadExport'),
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
		$evidence->prepareHtmlToHtml($_POST['html'])->saveWord();
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
			'step' => ExportStepEvidence::STEP1
		));
	}

	public function actionSectionPrepareWord()
	{
		var_dump($_POST);die;
		if(!empty($_POST) && isset($_POST['activityItems'])) {
			$itemsList = $_POST['activityItems'];
			$html = Evidence::getPrepareObjects($itemsList);
			$this->render("displayContext", [
				'html' => $html,
			]);
		} else {
			return $this->redirect(Yii::app()->createUrl("/evidence/evidence/prepare"));
		}
	}

	public function actionSaveExport()
	{
		$output = [];
		parse_str($_POST['exportData'], $output); // here $step and $saveExport

		$currentStep = $output['step'];
		$exportName = $output['saveExport'];
		$exportData = $_POST['html'];

		$model = new ExportStepEvidence;
		$model->name = trim($exportName);
		$model->$currentStep =  trim($exportData);
		if($model->save()) {
			CurrStepEvidence::setCurrentStep($exportData, $currentStep);
			setcookie("LoadExport", 1, time()+3600*24*10, "/");
			echo json_encode(['flag' => true, 'redirect' => Yii::app()->createUrl("/evidence/evidence/prepare")]);
			return;
		}

		echo json_encode(['flag' => false, 'error' => $model->getErrors()]);
	}

	public function actionLoadExport()
	{
		$exportId = $_POST['exportId'][0];
		$model = ExportStepEvidence::model()->find('id='. $exportId);
		if(!empty($model)) {
			CurrStepEvidence::setCurrentStep($model->step1, ExportStepEvidence::STEP1);
			setcookie("LoadExport", 1, time()+3600*24*10, "/");
			echo json_encode(['flag' => true, 'redirect' => Yii::app()->createUrl("/evidence/evidence/prepare")]);
			return;
		}

		echo json_encode(['flag' => false, 'error' => 'Export by current name not found']);
	}
}

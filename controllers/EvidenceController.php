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
				'actions'=>array('prepare', 'sectionPrepareWord', 'saveToWord', 'saveExport'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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
			'modals' => $this->renderPartial("_modals"),
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

	public function actionSaveToWord()
	{
		$evidence = new Evidence;
		$evidence->prepareHtmlToHtml($_POST['html'])->saveWord();
		echo 'success';
	}

	public function actionSaveExport()
	{
		$saveExport = $_POST['step1'];

		$model = new ExportStepEvidence;
		$model->step1 =  $saveExport;
		$model->save();
	}
}

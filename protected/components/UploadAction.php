<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\base\ErrorException;
use app\components\helpers\StringHelper;

class UploadAction extends Action {

	/**
	 * @var bool
	 */
	public $disableCsrf = true;

	public function init() {
		\Yii::$app->response->format = Response::FORMAT_JSON;

		if ($this->disableCsrf) {
			\Yii::$app->request->enableCsrfValidation = false;
		}
	}

	public function run() {
		$response = array();
		try {
			if ($_FILES['ajax-file']['error'] > 0) {
				throw new ErrorException('An error ocurred when uploading.');
			}

			$oldName = StringHelper::sanitize($_FILES['ajax-file']['name']);
			$filename = time().'-'.$oldName;
			$relPath = '/assets/upload/'.$filename;
			$absPath = FileHelper::normalizePath(Yii::getAlias('@webroot').$relPath);
			$dirPath = dirname($absPath);
			if (!file_exists($dirPath)) {
				mkdir($dirPath, 0777, true);
			}
			if (!move_uploaded_file($_FILES['ajax-file']['tmp_name'], $absPath)) {
				throw new Exception('Error uploading file - check destination is writeable.');
			}

			$response = [
				'value'=>$relPath,
				'url'=>Url::base(true).$relPath,
				'label'=>$oldName,
				'status'=>'success'
			];
		} catch (\yii\base\ErrorException $ex) {
			$response = [
				'message'=>$ex->getMessage(),
				'status'=>'error'
			];
		}
		return $response;
	}

}

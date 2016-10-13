<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\helpers\Url;
use yii\base\ErrorException;
use app\components\helpers\FileHelper;

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

			$orgName = $_FILES['ajax-file']['name'];
			$tmpPath = FileHelper::createPathForSave(
				FileHelper::getTemporaryFilePath($orgName)
			);
			$value = basename($tmpPath);
			$label = $orgName;
			$url = FileHelper::getTemporaryFileUrl($value);

			// move upload file to temporary directory
			$dirPath = dirname($tmpPath);
			if (!file_exists($dirPath))
				mkdir($dirPath, 0777, true);
			if ( !move_uploaded_file($_FILES['ajax-file']['tmp_name'], $tmpPath) )
				throw new ErrorException('Error uploading file - check destination is writeable.');

			$response = [
				'value'=>$value,
				'url'=>$url,
				'label'=>$label,
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

<?php

namespace app\components;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use app\components\helpers\FileHelper;

class UploadAction extends \yii\base\Action {

	/**
	 * @var bool
	 */
	public $disableCsrf = true;

	/**
	 * @var string
	 */
	public $fileIndex = 'ajax-file';

	public function init() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		if ($this->disableCsrf) {
			\Yii::$app->request->enableCsrfValidation = false;
		}
	}

	public function run() {
		$response = array();
		try {
			if ( !isset($_FILES[$this->fileIndex]) ) {
				throw new BadRequestHttpException('Invalid request.');
			}

			$file = $_FILES[$this->fileIndex];
			if ($file['error'] > 0) {
				throw new ServerErrorHttpException('An error ocurred when uploading.');
			}

			$this->validateFileUpload($file);
			$response = $this->saveUploadFile($file);
		} catch (\yii\base\ErrorException $ex) {
			$response = [
				'message'=>$ex->getMessage(),
				'status'=>'error'
			];
		}
		return $response;
	}

	protected function saveUploadFile($file) {
		$uploadName = $file['name'];
		$filePath = FileHelper::createPathForSave(FileHelper::getTemporaryFilePath($uploadName));
		$filename = basename($filePath);
		$url = FileHelper::getTemporaryFileUrl($filename);
		if ( !move_uploaded_file($file['tmp_name'], $filePath) )
			throw new ServerErrorHttpException('Error saving file to server.');
		return [
			'value'=>$filename,
			'url'=>$url,
			'label'=>$uploadName,
			'status'=>'success'
		];
	}

	protected function validateFileUpload($file) {
		// check allowed file types		
	}

}

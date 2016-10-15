<?php

namespace app\components;

use app\components\helpers\FileHelper;

class RedactorUploadAction extends UploadAction {

	/**
	 * @var string
	 */
	public $fileIndex = 'file';

	protected function saveUploadFile($file) {
		$uploadName = $file['name'];
		$filePath = FileHelper::createPathForSave(FileHelper::getStoragePath('editor/'.$uploadName));
		$filename = basename($filePath);
		$url = FileHelper::getStorageUrl('editor/'.$filename);
		if ( !move_uploaded_file($file['tmp_name'], $filePath) )
			throw new ServerErrorHttpException('Error saving file to server.');
		return [
			'id'=>$filename,
			'filelink'=>$url,
		];
	}

}

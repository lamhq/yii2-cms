<?php

namespace app\components;

use app\components\helpers\StorageHelper;

class RedactorUploadAction extends UploadAction {

	/**
	 * @var string
	 */
	public $fileIndex = 'file';

	protected function saveUploadFile($file) {
		$uploadName = $file['name'];
		$filePath = StorageHelper::createPathForSave(StorageHelper::getStoragePath('editor/'.$uploadName));
		$filename = basename($filePath);
		$url = StorageHelper::getStorageUrl('editor/'.$filename);
		if ( !move_uploaded_file($file['tmp_name'], $filePath) )
			throw new ServerErrorHttpException('Error saving file to server.');
		return [
			'id'=>$filename,
			'filelink'=>$url,
		];
	}

}

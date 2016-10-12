<?php
namespace app\components\helpers;
use yii;
use yii\helpers\FileHelper;

/**
 * @author Lam Huynh <lamhq.com>
 */
class FileHelper {

	static public function getUploadFilePath($filename, $absolute=false) {
		$result = '/assets/upload/'.$filename;
		return FileHelper::normalizePath(
			$absolute ? Yii::getAlias('@webroot') . $result : $result
		);
	}

	static public function getUploadFileUrl($file) {

	}

}

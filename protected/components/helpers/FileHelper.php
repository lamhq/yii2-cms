<?php
namespace app\components\helpers;
use yii;
use yii\helpers\Url;
use yii\helpers\FileHelper as YiiFileHelper;

/**
 * Provide file helper method for application level
 * 
 * @author Lam Huynh <lamhq.com>
 */
class FileHelper extends YiiFileHelper {

	static protected function getModelUploadPath($model) {
		if ($model instanceof \app\models\Post) {
			return 'post';
		}
		return '';
	}

	/**
	 * Returns url for storage item
	 *
	 * @param string $path if provided, append to the result
	 * @return string the absolute path for storage directory in system
	 */
	static public function getModelFileUrl($model, $filename) {
		if ($model->isNewRecord) return '';

		$parts = [
			Url::base(true),
			Yii::$app->params['storagePath'],
			self::getModelUploadPath($model),
			$model->id,
			rawurlencode($filename)
		];
		return implode('/', $parts);
	}

	/**
	 * Returns the absolute path for storage directory in system
	 *
	 * @param string $path if provided, append to the result
	 * @return string the absolute path for storage directory in system
	 */
	static public function getModelFilePath($model, $filename) {
		if ($model->isNewRecord) return '';
		
		$parts = [
			Yii::getAlias('@webroot'),
			Yii::$app->params['storagePath'],
			self::getModelUploadPath($model),
			$model->id,
			$filename
		];
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	/**
	 * Returns the absolute path for temporary directory in system
	 *
	 * @param string $path filename, relative file path or directory path (e.g. "abc.jpg","a/b/c.jpg")
	 * @return string the absolute path for an item in temporary directory
	 */
	static public function getTemporaryFilePath($filename) {
		$parts = [
			Yii::getAlias('@webroot'),
			Yii::$app->params['storagePath'],
			'tmp',
			$filename
		];
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	static public function getTemporaryFileUrl($filename) {
		$parts = [
			Url::base(true),
			Yii::$app->params['storagePath'],
			'tmp',
			rawurlencode($filename)
		];
		return implode('/', $parts);
	}

	static public function createPathForSave($path) {
		$parts = pathinfo($path);
		$i=1;
		while ( is_file($path) ) {
			$path = $parts['dirname'].DIRECTORY_SEPARATOR.$parts['filename'].$i.'.'.$parts['extension'];
			$i++;
		}
		if ( !file_exists(dirname($path)) )
			mkdir(dirname($path));
		return $path;
	}

}

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

	static public function getStorageUrl($path='') {
		$parts = [
			Url::base(true),
			Yii::$app->params['storagePath'],
			$path
		];
		return self::normalizeFileUrl(implode('/', $parts));
	}

	static public function getStoragePath($path='') {
		$parts = [
			Yii::getAlias('@webroot'),
			Yii::$app->params['storagePath'],
			$path
		];
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	static public function getTemporaryFileUrl($path) {
		return self::getStorageUrl('tmp/'.$path);
	}

	static public function getTemporaryFilePath($path) {
		return self::getStoragePath('tmp'.DIRECTORY_SEPARATOR.$path);
	}

	static public function getModelImageUrl($model, $filename, $width=null, $height=null, $options=[]) {
		$srcPath = self::getModelFilePath($model, $filename);
		if ( !$filename || (!$width && !$height) ) {
			return is_file($srcPath) ? self::getModelFileUrl($model, $filename) : '';
		}

		$parts = pathinfo($filename);
		$p = sprintf('post/%s_%s_%sx%s.%s', 
			$model->id, $parts['filename'], $width, $height, $parts['extension']);
		$resizePath = self::getTemporaryFilePath($p);
		$resizeUrl = self::getTemporaryFileUrl($p);

		$options = array_merge(['width'=>$width, 'height'=>$height], $options);
		ImageHelper::resize($srcPath, $resizePath, $options);
		return is_file($resizePath) ? $resizeUrl : '';
	}

	static public function getModelFileUrl($model, $path) {
		if ($model->isNewRecord) return '';
		$parts = [
			Url::base(true),
			Yii::$app->params['storagePath'],
			self::getModelUploadPath($model),
			$model->id,
			$path
		];
		return self::normalizeFileUrl(implode('/', $parts));
	}

	static public function getModelFilePath($model, $path) {
		if ($model->isNewRecord) return '';
		
		$parts = [
			Yii::getAlias('@webroot'),
			Yii::$app->params['storagePath'],
			self::getModelUploadPath($model),
			$model->id,
			$path
		];
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	static protected function getModelUploadPath($model) {
		if ($model instanceof \app\models\Post) {
			return 'post';
		}
		return '';
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

	static protected function normalizeFileUrl($url) {
		return dirname($url).'/'.rawurlencode(basename($url));
	}

}

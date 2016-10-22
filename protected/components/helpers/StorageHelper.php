<?php
namespace app\components\helpers;
use yii;
use yii\helpers\Url;
use lamhq\php\helpers\ImageHelper;

/**
 * Provide file helper method for application level
 * 
 * @author Lam Huynh <lamhq.com>
 */
class StorageHelper {

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
		if ( !is_file($srcPath) ) {
			return self::getNoImageUrl($width, $height);
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
		return self::getStorageUrl( self::getModelUploadPath($model, $path) );
	}

	static public function getModelFilePath($model, $path) {
		if ($model->isNewRecord) return '';
		return self::getStoragePath( self::getModelUploadPath($model, $path) );
	}

	static protected function getModelUploadPath($model, $path) {
		if ($model instanceof \app\models\Post) {
			return 'post/'.$model->id.'/'.$path;
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

	static protected function getNoImageUrl($width=null, $height=null) {
		$src = self::getStoragePath('no-image.jpg');
		if ( !is_file($src) ) return 'http://placehold.it/230x200?text='.urlencode(Yii::$app->params['siteTitle']);

		$filename = "no-image{$width}x{$height}.jpg";
		$dst = self::getTemporaryFilePath($filename);
		$url = self::getTemporaryFileUrl($filename);
		ImageHelper::resize($src, $dst, ['width'=>$width, 'height'=>$height]);
		return $url;
	}

}

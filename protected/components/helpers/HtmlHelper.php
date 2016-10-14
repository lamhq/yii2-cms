<?php
namespace app\components\helpers;
use yii\helpers\Html;

/**
 * @author Lam Huynh <lamhq.com>
 */
class HtmlHelper
{
	static public function img($src, $width, $height, $options = []) {
		$options = array_merge([
			'class' => 'img-responsive',
			'height' => $height,
			'width' => $width
		], $options);
		if ($src) {
			return Html::img($src, $options);
		}

		return self::placeHolder($width, $height);
	}

	static public function placeHolder($width, $height) {
		$src = FileHelper::getStoragePath('no-image.jpg');
		$filename = "no-image{$width}x{$height}.jpg";
		$dst = FileHelper::getTemporaryFilePath($filename);
		$url = FileHelper::getTemporaryFileUrl($filename);
		ImageHelper::resize($src, $dst, ['width'=>$width, 'height'=>$height]);
		$options = [
			'class' => 'img-responsive',
			'height' => $height,
			'width' => $width
		];
		return Html::img($url, $options);
	}

}

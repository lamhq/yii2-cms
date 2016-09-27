<?php

namespace setup\components;
use Yii;
use yii\helpers\Html;

class Helper {

	public static function isWebsiteInstalled() {
		return is_file(Yii::getAlias('@app').DS.'protected'.DS.'config'.DS.'_common.php');
	}
}

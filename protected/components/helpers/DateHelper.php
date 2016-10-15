<?php
namespace app\components\helpers;

/**
 * @author Lam Huynh <lamhq.com>
 */
class DateHelper
{
	static public function getDatepickerDatetimeFormat() {
		return 'DD/MM/YYYY HH:mm';
	}

	static public function getAppDatetimeFormat() {
		$f = \Yii::$app->formatter->datetimeFormat;
		return $f;
	}
	
	static public function toDbDatetime($value) {
		$f = str_replace('php:', '', self::getAppDatetimeFormat());
		$t = date_create_from_format($f, $value);
		return $t ? $t->format('Y-m-d H:i:s') : null;
	}

}

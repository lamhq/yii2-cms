<?php
namespace app\components\helpers;
use Yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class DateHelper
{
	/**
	 * get datetime format for bootstrap datepicker client library
	 * see: https://github.com/Eonasdan/bootstrap-datetimepicker
	 * 
	 * @return string
	 */
	static public function getDatepickerDatetimeFormat() {
		return 'DD/MM/YYYY HH:mm';
	}

	/**
	 * get datetime format use in yii formatter
	 * see: http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#$datetimeFormat-detail
	 * 
	 * @return string
	 */
	static public function getAppDatetimeFormat() {
		$f = \Yii::$app->formatter->datetimeFormat;
		return $f;
	}
	
	/**
	 * convert a datetime value used in application to datetime value stored in database, 
	 * timezone conversion included
	 * @param  string $value String representing the time. 
	 * @return string the formatted result in Y-m-d H:i:s format
	 */
	static public function toDbDatetime($value) {
		$f = str_replace('php:', '', self::getAppDatetimeFormat());
		$t = \DateTime::createFromFormat( $f, $value, new \DateTimeZone(Yii::$app->timezone) );
		if (!$t) return null;
		$t->setTimezone(new \DateTimeZone(Yii::$app->formatter->defaultTimeZone));
		return $t->format('Y-m-d H:i:s');
	}

	/**
	 * convert a datetime value stored in database to datetime value used in application, 
	 * timezone conversion included
	 * @param  string $value String representing the time. http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asDatetime%28%29-detail
	 * @return string the formatted result.
	 */
	static public function toAppDatetime($value) {
		return Yii::$app->formatter->asDateTime($value);
	}

}

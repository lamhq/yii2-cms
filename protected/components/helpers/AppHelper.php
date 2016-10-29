<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AppHelper
{
	static public function setSuccess($message) {
		Yii::$app->getSession()->setFlash('alert', [
			'body'=>$message,
			'options'=>['class'=>'alert-success']
		]);
	}

	static public function setError($message) {
		Yii::$app->getSession()->setFlash('alert', [
			'body'=>$message,
			'options'=>['class'=>'alert-error']
		]);
	}

	static public function getPageTitle() {
		$t = array_filter([ Yii::$app->view->title, Yii::$app->params['siteTitle'] ]);
		return implode(' | ', $t);
	}

	static public function params($name) {
		return Yii::$app->params[$name];
	}

	/**
	 * Return a named singleton value. Its value will be generated on the first run
	 * 
	 * @param  string $name name of the value
	 * @param  Closure $func anonymous function used to generate the value
	 * @return mixed the value
	 */
	static public function singleton($name, $func) {
		$name = 'singleton-'.$name;
		if (!isset(Yii::$app->params[$name])) {
			Yii::$app->params[$name] = $func();
		}
		return Yii::$app->params[$name];
	}
}

<?php

namespace backend;
use Yii;
use yii\base\Controller;

class Module extends \yii\base\Module {

	public $defaultRoute = 'post';
	
	public function init() {
		parent::init();
		// set theme adminlte for backend module
		$theme = Yii::$app->view->theme;
		$theme->pathMap['@app/modules/backend/views'] = '@webroot/themes/adminlte/views';
		
		// set access rules
		$this->on(Controller::EVENT_BEFORE_ACTION, function($event) {
			Yii::$app->controller->attachBehavior('access', [
				'class' => 'yii\filters\AccessControl',
				'rules'=> Yii::$app->params['accessRules']
			]);
		});
		Yii::$app->user->loginUrl = ['/backend/site/login'];
		$this->layout = 'main';
	}

}

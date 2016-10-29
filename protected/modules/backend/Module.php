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
			$accessRule = [
				// guest
				[
					'allow' => true,
					'controllers' => ['backend/site'],
					'actions' => ['login', 'error', 'forgot-password', 'reset-password'],
					'roles' => ['?'],
				],
				// backend
				[
					'allow' => true,
					'controllers' => ['backend/site'],
					'roles' => ['@'],
				],
				// page
				[
					'allow' => true,
					'controllers' => ['backend/page'],
					'roles' => ['managePage'],
				],
				// post
				[
					'allow' => true,
					'controllers' => ['backend/post'],
					'roles' => ['managePost'],
				],
				// category
				[
					'allow' => true,
					'controllers' => ['backend/category'],
					'roles' => ['manageCategory'],
				],
				// email-template
				[
					'allow' => true,
					'controllers' => ['backend/email-template'],
					'roles' => ['manageEmailTemplate'],
				],
				// slideshow
				[
					'allow' => true,
					'controllers' => ['backend/slideshow'],
					'roles' => ['manageSildeShow'],
				],
				// setting
				[
					'allow' => true,
					'controllers' => ['backend/setting'],
					'roles' => ['configuration'],
				],
				// account
				[
					'allow' => true,
					'controllers' => ['backend/account'],
					'roles' => ['manageAccount'],
				],
				// role
				[
					'allow' => true,
					'controllers' => ['backend/role'],
					'roles' => ['manageRole'],
				],
				// permission
				[
					'allow' => true,
					'controllers' => ['backend/permission'],
					'roles' => ['managePermission'],
				],
				// deny all
				[
					'allow' => false,
					'roles' => ['?'],
				],
			];

			Yii::$app->controller->attachBehavior('access', [
				'class' => 'yii\filters\AccessControl',
				'rules'=> $accessRule
			]);
		});
		Yii::$app->user->loginUrl = ['/backend/site/login'];
		$this->layout = 'main';
	}

}

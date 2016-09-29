<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\LoginForm;
use backend\models\AccountForm;
use app\models\ForgotPasswordForm;
use app\models\ResetPasswordForm;
use app\components\Helper;
use yii\filters\VerbFilter;

class SiteController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		return $this->render('index');
	}

	public function actionLogin() {
		$this->layout = 'blank';
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model
			]);
		}
	}

	public function actionLogout() {
		Yii::$app->user->logout();
		return $this->redirect(['/backend']);
	}

	public function actionAccount() {
		$user = Yii::$app->user->identity;
		$model = new AccountForm();
		$model->username = $user->username;
		$model->email = $user->email;
		if ($model->load($_POST) && $model->validate()) {
			$user->username = $model->username;
			$user->email = $model->email;
			if ($model->password) {
				$user->setPassword($model->password);
			}
			$user->save();
			Yii::$app->session->setFlash('alert', [
				'options' => ['class' => 'alert-success'],
				'body' => Yii::t('app', 'Your account has been successfully saved')
			]);
			return $this->refresh();
		}
		return $this->render('account', ['model' => $model]);
	}

	/**
	 * @return string|Response
	 */
	public function actionForgotPassword()
	{
		$model = new ForgotPasswordForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->sendEmail();
        	Helper::setSuccess(Yii::t('app', 'An email will be sent to your inbox if your account existed in system.'));
			return $this->refresh();
		}

		$this->layout = 'blank';
		return $this->render('forgot-password', [
			'model' => $model,
		]);
	}

	/**
	 * @param $token
	 * @return string|Response
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Helper::setSuccess(Yii::t('app', 'New password was saved. You can login with the new password.'));
			return $this->redirect(['/backend/site/login']);
		}

		$this->layout = 'blank';
		return $this->render('reset-password', [
			'model' => $model,
		]);
	}

}

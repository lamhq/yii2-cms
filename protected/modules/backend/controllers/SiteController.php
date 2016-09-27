<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\LoginForm;
use backend\models\AccountForm;
use app\models\PasswordResetRequestForm;
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
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	$model->sendEmail();
            Yii::$app->getSession()->setFlash('alert', [
                'body'=>Yii::t('app', 'Check your email for further instructions.'),
                'options'=>['class'=>'alert-success']
            ]);
            return $this->refresh();
        }

		$this->layout = 'blank';
        return $this->render('forgot-password', [
            'model' => $model,
        ]);
    }
}

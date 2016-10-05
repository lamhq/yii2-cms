<?php

namespace backend\controllers;

use Yii;
use backend\models\SettingForm;
use app\components\helpers\AppHelper;
use yii\web\Controller;

/**
 * PostController implements the CRUD actions for Post model.
 */
class SettingController extends Controller
{

	/**
	 * Creates a new Post model.
	 * If creation is successful, the browser will be redirected to the 'update' page.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$model = new SettingForm();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			AppHelper::setSuccess(Yii::t('app', 'Data saved.'));
			return $this->refresh();
		} else {
			return $this->render('index', [
				'model' => $model,
			]);
		}
	}

}

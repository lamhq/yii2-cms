<?php

namespace backend\controllers;

use Yii;
use app\components\helpers\AppHelper;
use backend\models\Permission;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class PermissionController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Permission models.
	 * @return mixed
	 */
	public function actionIndex($id=null)
	{
		$model = $id ? $this->findModel($id) : new Permission(['scenario'=>'insert']);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			AppHelper::setSuccess(Yii::t('app', 'Data saved.'));
			return $this->redirect(['index', 'id' => $model->name]);
		} else {
			return $this->render('index', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Permission model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Permission model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Permission the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Permission::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

<?php

namespace backend\controllers;

use Yii;
use app\components\helpers\AppHelper;
use backend\models\Role;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
	 * Lists all Role models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => Role::getRoles(),
			'sort' => [
				'attributes' => ['name'],
			],
			'pagination'=>['defaultPageSize'=>Yii::$app->params['defaultPageSize']]
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Role model.
	 * If creation is successful, the browser will be redirected to the 'update' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Role(['scenario'=>'insert']);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::setSuccess(Yii::t('app', 'Data saved.'));
			return $this->redirect(['update', 'id' => $model->name]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Role model.
	 * If update is successful, the browser will be redirected to the 'update' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::setSuccess(Yii::t('app', 'Data saved.'));
			return $this->redirect(['update', 'id' => $model->name]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Role model.
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
	 * Finds the Role model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Role the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Role::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

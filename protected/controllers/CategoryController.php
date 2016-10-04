<?php

namespace app\controllers;

use app\models\Category;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * @author Lam Huynh <lamhq.com>
 */
class CategoryController extends Controller
{
	/**
	 * @param $slug
	 * @throws NotFoundHttpException
	 */
	public function actionView($slug)
	{
		$model = Category::find()
			->andWhere(['slug'=>$slug])
			->active()
			->one();
		if (!$model) {
			throw new NotFoundHttpException;
		}
		$query = $model->getPosts()
			->published()
			->with(['category', 'tags']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination'=>['defaultPageSize'=>Yii::$app->params['defaultPageSize']]
		]);

		return $this->render('view', [
			'model'=>$model,
			'dataProvider' => $dataProvider,
		]);
	}

}

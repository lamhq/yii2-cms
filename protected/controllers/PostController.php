<?php

namespace app\controllers;

use app\models\Post;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * @author Lam Huynh <lamhq.com>
 */
class PostController extends Controller
{
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$query = Post::find()->published()->with('author');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'=>['defaultOrder' => ['published_at'=>SORT_DESC]],
			'pagination'=>['defaultPageSize'=>Yii::$app->params['defaultPageSize']]
		]);

		return $this->render('index', ['dataProvider'=>$dataProvider]);
	}

	/**
	 * @param $slug
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($slug)
	{
		$model = Post::find()
			->published()
			->andWhere(['slug'=>$slug])
			->with(['category', 'tags'])
			->one();
		if (!$model) {
			throw new NotFoundHttpException;
		}

		return $this->render('view', ['model'=>$model]);
	}

	public function actionSearch($s) {
		$query = Post::find()
			->published()->byKeyword($s);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination'=>['defaultPageSize'=>Yii::$app->params['defaultPageSize']]
		]);
		return $this->render('search', [
			'term'=>trim($s, '"'),
			'dataProvider' => $dataProvider,
		]);
	}
}

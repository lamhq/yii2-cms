<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Post;
use backend\models\Category;
use app\models\User;
use app\components\EnumColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

	<p>
		<?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?php Pjax::begin(); ?>    
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				'title',
				[
					'class' => EnumColumn::className(),
					'attribute' => 'featured_image',
					'format' => 'html',
					'value'=>function ($model, $key, $index, $column) {
						return Html::img($model->getFeaturedImageUrl(230, 200));
					}
				],
				[
					'class' => EnumColumn::className(),
					'attribute' => 'category_id',
					'enum' => Category::getListData()
				],
				[
					'class' => EnumColumn::className(),
					'attribute' => 'created_by',
					'enum' => User::getListData()
				],
				'published_at:date',

				[
					'class' => 'yii\grid\ActionColumn',
					'template'=>'{update} {delete}'
				],
			],
		]); ?>
	<?php Pjax::end(); ?>
</div>

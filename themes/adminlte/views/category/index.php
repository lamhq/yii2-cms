<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\Menu;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
	<div class="row">
		<div class="col-md-4">
			<p><?= Html::a(Yii::t('app', 'Create'), ['index'], ['class' => 'btn btn-success']) ?></p>
			<div class="category-nav">
				<?= Menu::widget([
					'options' => ['class'=>'tree'],
					'items'=>Category::getCategoryMenuItems()
				]) ?>
			</div>
		</div>
		<div class="col-md-8">
			<?= $this->render('_form', ['model'=>$model]) ?>
		</div>
	</div>
</div>
<style>
.category-nav { border: solid 1px #ecf0f5 }
.category-nav .in-active a { font-style: italic; color: #ccc; }
</style>
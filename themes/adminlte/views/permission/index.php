<?php

use yii\helpers\Html;
use yii\widgets\Menu;
use backend\models\Permission;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->title = Yii::t('app', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-index">
	<div class="row">
		<div class="col-md-4">
			<p><?= Html::a(Yii::t('app', 'Create'), ['index'], ['class' => 'btn btn-success']) ?></p>
			<div class="permission-nav">
				<?= Menu::widget([
					'options' => ['class'=>'tree'],
					'items'=>Permission::getPermissionMenuItems()
				]) ?>
			</div>
		</div>
		<div class="col-md-8">
			<?= $this->render('_form', ['model'=>$model]) ?>
		</div>
	</div>
</div>
<style>
.permission-nav { border: solid 1px #ecf0f5 }
.permission-nav .in-active a { font-style: italic; color: #ccc; }
</style>
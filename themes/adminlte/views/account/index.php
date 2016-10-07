<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\components\EnumColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<p>
		<?= Html::a(Yii::t('app', 'Create Account'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'username',
			'email:email',
			'created_at:date',
			[
				'class' => EnumColumn::className(),
				'attribute' => 'status',
				'enum' => User::getStatuses()
			],

			[
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
		],
	]); ?>
</div>

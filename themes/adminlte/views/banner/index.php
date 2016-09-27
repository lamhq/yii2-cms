<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banner Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <p>
        <?= Html::a('Create Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
				'attribute'=>'image',
				'format'=>'raw',
				'value'=>function ($model, $key, $index, $column) {
					return Helper::holderImage($model->getImageUrl(400, 150), 400, 150);
				},
			],
            [
				'attribute'=>'type',
				'value'=>function ($model, $key, $index, $column) {
					return Yii::$app->formatter->formatEnum($model->type, 'banner_type');
				},
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

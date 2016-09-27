<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Banner Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
				'attribute'=>'image',
				'format'=>'raw',
				'value'=>  app\components\Helper::holderImage($model->getImageUrl(400, 150), 400, 150),
			],
            [
				'attribute'=>'type',
				'value'=> Yii::$app->formatter->formatEnum($model->type, 'banner_type'),
			],
        ],
    ]) ?>

</div>

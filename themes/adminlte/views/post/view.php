<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

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
            [
				'attribute'=>'url',
				'format'=>'raw',
				'value'=> Html::a($model->url, $model->url),
			],
            'title',
            'short_content:html',
            'content:html',
            [
				'attribute'=>'image',
				'format'=>'raw',
				'value'=>  app\components\Helper::holderImage($model->getImageUrl(400, 150), 400, 150),
			],
            [
				'attribute'=>'status',
				'value'=> Yii::$app->formatter->formatEnum($model->status, 'status'),
			],
            'created_at:date',
            [
				'attribute'=>'author_id',
				'value'=> $model->author ? $model->author->username : '',
			],
            [
				'attribute'=>'category',
				'value'=> implode(', ', \yii\helpers\ArrayHelper::map($model->categories, 'id', 'name')),
			],
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = 'Update Banner: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Banner Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banner-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

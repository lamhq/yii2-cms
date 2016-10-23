<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Slideshow */

$this->title = Yii::t('app', 'Create Slideshow');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slideshows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slideshow-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

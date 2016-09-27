<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\AjaxUpload;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->widget(AjaxUpload::className(), [
		'uploadUrl' => Url::to(['/site/ajaxUpload']),
		'extensions' => ['jpg', 'jpeg', 'gif', 'png'],
		'maxSize' => 4000,
	]) ?>

    <?= $form->field($model, 'type')->dropDownList(app\models\Banner::getListData()) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

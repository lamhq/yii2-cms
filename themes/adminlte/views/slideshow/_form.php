<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\AjaxUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Slideshow */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slideshow-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'images')->widget(AjaxUpload::className(), [
		'url'=>['/site/upload'],
		'multiple' => true
	]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

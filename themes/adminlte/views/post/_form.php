<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_content')->widget(\yii\redactor\widgets\Redactor::className()) ?>

    <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className()) ?>

    <?= $form->field($model, 'featured_image')->widget(
	 \app\widgets\AjaxUpload::className(), [
		'uploadUrl' => yii\helpers\Url::to(['/site/ajaxUpload']),
		'extensions' => ['jpg', 'jpeg', 'gif', 'png'],
		'maxSize' => 4000,
	]) ?>

    <?= $form->field($model, 'uploadImages')->widget(\app\widgets\BannerUpload::className(), [
		'uploadUrl' => Url::to(['/site/ajaxUpload']),
		'extensions' => ['jpg', 'jpeg', 'gif', 'png'],
		'maxSize' => 4000,
		'multiple' => true,
	]) ?>
	
    <?= $form->field($model, 'status')->dropDownList(app\models\Lookup::items('status'), ['prompt'=>'-- Select --']) ?>

	<?php
	$tagNames = array_values(\app\models\Tag::getListData());
	?>
	<?= $form->field($model, 'tagValues')->widget(
		Select2::className(), [
		'data' => array_combine($tagNames, $tagNames),
		'options' => [
//			'placeholder'=>'Enter tags',
		],
		'pluginOptions' => [
			'multiple' => true,
			'tags' => true,
			'tokenSeparators' => [','],
		],
	]) ?>
	
    <?= $form->field($model, 'selectedCategories')->checkboxList(app\models\Category::getListData(), [
		'item' => function ($index, $label, $name, $checked, $value){
			return sprintf('<div class="checkbox">%s</div>',Html::checkbox($name, $checked, [
			   'value' => $value,
			   'label' => $label,
			   'class' => '',
			]));		
		}]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

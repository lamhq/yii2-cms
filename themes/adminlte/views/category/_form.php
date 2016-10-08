<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
	<div class="box-header with-border">
		<h3 class="box-title">
			<i class="fa fa-tags" aria-hidden="true"></i>
			<?= Html::encode($model->isNewRecord ? Yii::t('app', 'Create new Category') 
			: Yii::t('app', 'Update Category "{name}"', ['name'=>$model->name]) ) ?>
		</h3>
	</div>

	<div class="box-body">
		<?php $form = ActiveForm::begin(); ?>
		
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		
		<?= $form->field($model, 'parent_category_id')
			->dropdownList(Category::getCategoryDropdownList($model->id), ['encode'=>false, 'prompt'=>'']) ?>
		
		<?= $form->field($model, 'status')->checkbox() ?>
		
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
			<?php if (!$model->isNewRecord): ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
			<?php endif ?>
		</div>
		
		<?php ActiveForm::end(); ?>
	</div>

</div>

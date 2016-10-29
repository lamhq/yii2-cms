<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use backend\models\Permission;
use app\models\User;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form">

	<div class="box-header with-border">
		<h3 class="box-title">
			<i class="fa fa-tags" aria-hidden="true"></i>
			<?= Html::encode($model->isNewRecord ? Yii::t('app', 'Create new Permission')
			: Yii::t('app', 'Update Permission "{name}"', ['name'=>$model->name]) ) ?>
		</h3>
	</div>

	<div class="box-body">
		<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

		<?php if ($model->isNewRecord): ?>
			<?= $form->field($model, 'name')->textInput() ?>
		<?php else: ?>
			<?= $form->field($model, 'name')->textInput(['readonly'=>'readonly']) ?>
		<?php endif ?>

		<?= $form->field($model, 'description')->textArea() ?>

		<?= $form->field($model, 'childs')->widget(
			Select2::className(), [
			'data' => $model->getChildsListData(),
			'pluginOptions' => [
				'multiple' => true,
				'tags' => true,
				'tokenSeparators' => [','],
			],
		]) ?>

		<?= $form->field($model, 'parents')->widget(
			Select2::className(), [
			'data' => $model->getParentsListData(),
			'pluginOptions' => [
				'multiple' => true,
				'tags' => true,
				'tokenSeparators' => [','],
			],
		]) ?>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-6">
				<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
				
				<?php if (!$model->isNewRecord): ?>
				<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->name], [
					'class' => 'btn btn-danger pull-right',
					'data' => [
						'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
				<?php endif ?>
			</div>
		</div>

		<?php ActiveForm::end(); ?>
	</div>

</div>

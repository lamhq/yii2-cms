<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use backend\models\Role;
use app\models\User;
use app\widgets\TreeCheckbox;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("app.setupRoleForm();");
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

	<?php if ($model->isNewRecord): ?>
		<?= $form->field($model, 'name')->textInput() ?>
	<?php else: ?>
		<?= $form->field($model, 'name')->textInput(['readonly'=>'readonly']) ?>
	<?php endif ?>

	<?= $form->field($model, 'description')->textArea() ?>


	<div class="form-group">
		<label class="control-label col-sm-3"><?= $model->getAttributeLabel('access') ?></label>
		<div class="col-sm-6">
			<?= Html::activeDropDownList($model, 'access', Role::getAccessOptions(), ['class'=>'form-control', 'prompt'=>Yii::t('app', 'None')]) ?>
			<?= TreeCheckbox::widget([
				'model'=>$model,
				'attribute'=>'permissions',
				'tree'=>Role::getPermissionTree()
			]); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>

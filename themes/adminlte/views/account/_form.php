<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\Role;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->isNewRecord): ?>
    	<?= $form->field($model, 'username')->textInput() ?>
	<?php else: ?>
    	<?= $form->field($model, 'username')->textInput(['readonly'=>'readonly']) ?>
    <?php endif ?>

    <?= $form->field($model, 'email')->textInput() ?>
    
    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'repeatPassword')->passwordInput() ?>

    <?= $form->field($model, 'status')->checkbox(['uncheck'=>User::STATUS_INACTIVE]) ?>

    <?= $form->field($model, 'roles')->checkboxList(Role::getListData()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

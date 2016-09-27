<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

$this->title = Yii::t('app', 'Reset password');
?>
<p class="login-box-msg"><?= Yii::t('app', 'Reset password') ?></p>

<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
<div class="body">
    <?php echo $form->field($model, 'password')->passwordInput() ?>
</div>
<div class="footer">
    <p><?php echo Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-primary btn-flat btn-block',
    ]) ?></p>
</div>
<?php ActiveForm::end(); ?>

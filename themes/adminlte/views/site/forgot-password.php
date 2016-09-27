<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title =  Yii::t('app', 'Forgot password');
?>
<p class="login-box-msg"><?= Yii::t('app', 'Reset your password') ?></p>

<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
<div class="body">
    <?php echo $form->field($model, 'email') ?>
</div>
<div class="footer">
    <p><?php echo Html::submitButton(Yii::t('app', 'Send'), [
        'class' => 'btn btn-primary btn-flat btn-block',
    ]) ?></p>
</div>
<?php ActiveForm::end(); ?>

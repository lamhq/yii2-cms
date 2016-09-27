<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = Yii::t('app', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login-page';
?>
<div class="login-box">
	<div class="login-logo">
		<?php echo Html::encode(Yii::$app->name) ?>
	</div>
	<div class="login-box-body">
		<p class="login-box-msg"><?= Yii::t('app', 'Sign in to start your session') ?></p>
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
		<div class="body">
			<?php echo $form->field($model, 'username') ?>
			<?php echo $form->field($model, 'password')->passwordInput() ?>
			<?php echo $form->field($model, 'rememberMe')->checkbox(['class'=>'simple']) ?>
		</div>
		<div class="footer">
			<p><?php echo Html::submitButton(Yii::t('app', 'Sign me in'), [
					'class' => 'btn btn-primary btn-flat btn-block',
					'name' => 'login-button'
				]) ?></p>
		</div>
		<?php ActiveForm::end(); ?>
		<a href="<?= yii\helpers\Url::to(['/backend/site/forgot-password']) ?>">I forgot my password</a>
	</div>
</div>
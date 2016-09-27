<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\Theme::register($this);

$this->params['body-class'] = array_key_exists('body-class', $this->params) ?
	$this->params['body-class']
	: null;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>

	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body class="<?= ArrayHelper::getValue($this->params, 'body-class') ?> skin-blue login-page">
<?php $this->beginBody() ?>
	<div class="login-box">
		<div class="login-logo">
			<?php echo Html::encode(Yii::$app->name) ?>
		</div>
		<div class="login-box-body">
			<?php if (Yii::$app->session->hasFlash('alert')):?>
				<?php echo \yii\bootstrap\Alert::widget([
					'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
					'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
				])?>
			<?php endif; ?>
			<?= $content ?>
		</div>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
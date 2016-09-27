<?php
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\Theme::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?= $this->render('_head') ?>
</head>
<body class="skin-blue sidebar-mini <?= ArrayHelper::getValue($this->params, 'body-class') ?>">
<?php $this->beginBody() ?>
<div class="wrapper">
	<?= $this->render('_header') ?>
	
	<?php echo $this->render('_sidebar') ?>
		
	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				<?= $this->title ?>
				<?php if(isset($this->params['subtitle'])): ?>
					<small><?= $this->params['subtitle'] ?></small>
				<?php endif; ?>
			</h1>

			<?= Breadcrumbs::widget([
				'homeLink'=>[
					'label'=>'Home',
					'url'=>['/backend']
				],
				'tag'=>'ol',
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php if (Yii::$app->session->hasFlash('alert')):?>
				<?php echo \yii\bootstrap\Alert::widget([
					'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
					'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
				])?>
			<?php endif; ?>
			<div class="main-content">
				<?= $content ?>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->
</div>

<div class="modal" id="app-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			
			<div class="modal-body"></div>
			<div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-left btn-cancel" type="button">Cancel</button>
                <button  data-dismiss="modal" class="btn btn-primary btn-ok" type="button">Ok</button>
            </div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?= $this->blocks['before_body_end'] ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

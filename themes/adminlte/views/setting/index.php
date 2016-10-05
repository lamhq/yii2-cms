<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;

$this->title = 'System Configuration';
?>
<?php $form = ActiveForm::begin([
	'id' => 'setting-form', 
	'layout' => 'horizontal'
]); ?>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">General</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			<?= $this->render('_general', ['model'=>$model, 'form'=>$form]) ?>
		</div>
	</div>
	<!-- /.tab-content -->
</div>
<p><button type="submit" class="btn btn-primary">Save</button></p>
<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\models\Post;
use backend\models\Category;
use app\models\Tag;
use app\components\helpers\DateHelper;
use app\widgets\AjaxUpload;
use trntv\yii\datetime\DateTimeWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'featuredImage')->widget(AjaxUpload::className(), [
		'url'=>['/site/upload'],
	]) ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

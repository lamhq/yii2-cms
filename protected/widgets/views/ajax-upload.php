<?php
/* @var $this yii\web\View */
/* @var $options array */
use yii\helpers\Html;
?>
<div id="<?= $id ?>" class="ajax-upload-widget">
	<div class="btn btn-primary btn-file">
		<span class="">Choose file</span>
		<input type="file" <?= $options['multiple'] ? 'multiple' : null ?>/>
	</div>
	
	<div class="files"></div>
</div>
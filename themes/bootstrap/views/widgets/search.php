<?php

/* @var $this yii\web\View */
/* @var $items array */
use yii\helpers\Url;
?>
<aside class="widget clearfix">
<form action="<?= Url::to(['post/search']) ?>" class="search-form form-inline" method="get" role="search">
	<div class="form-group">
		<label>
		<span class="screen-reader-text sr-only">Search for:</span>
		<input type="search" name="s" value="<?= \Yii::$app->request->get('s') ?>" placeholder="Search …" class="search-field form-control">
		</label>
		<input type="submit" value="Search" class="search-submit btn btn-primary">
	</div>
	<!-- .form-group -->
</form>
</aside>
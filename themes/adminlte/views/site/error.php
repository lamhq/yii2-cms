<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$code = property_exists($exception, 'statusCode') ? $exception->statusCode : 500;
?>
<div class="error-page">
	<h2 class="headline text-red"><?= $code ?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
		<p><?= nl2br(Html::encode($message)) ?></p>
	</div>
</div>

<?php

/* @var $this yii\web\View */
/* @var $items array */
use yii\widgets\Menu;
?>
<aside class="widget clearfix">
	<h2 class="widget-title">Recent Posts</h2>
	<?= Menu::widget([
		'items'=>$items
	]) ?>
</aside>

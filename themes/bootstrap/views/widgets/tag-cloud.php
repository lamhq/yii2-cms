<?php
/* @var $this yii\web\View */
/* @var $items array */
use yii\widgets\Menu;
?>
<aside class="widget clearfix">
	<h2 class="widget-title">Tags</h2>
	<?= Menu::widget([
		'items'=>$items,
		'itemOptions'=>['tag'=>'span'],
		'options'=>['tag'=>'div', 'class'=>'tag-cloud'],
	]) ?>
</aside>
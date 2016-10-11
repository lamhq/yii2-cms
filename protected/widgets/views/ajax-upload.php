<?php
/* @var $this yii\web\View */
/* @var $options array */
/* @var $items array */
use yii\helpers\Html;
$widget = $this->context;
?>
<div id="<?= $widget->id ?>" class="ajax-upload-widget">
	<div class="btn btn-primary btn-file">
		<span class="">Choose file</span>
		<input type="file" <?= $options['multiple'] ? 'multiple' : null ?>/>
	</div>
	
	<div class="loader fa fa-spinner fa-spin fa-fw hide"></div>
	
	<div class="files row">
		<?php foreach($items as $k => $item): ?>
		<?php $name = sprintf('%s[%s]', $options['name'], $k) ?>

		<div class="item col-md-3"><div class="inn">
			<img src="<?= $item['url'] ?>" alt="" class="img-responsive" />
			<p class="name"><?= $item['label'] ?></p>
			<a class="remove fa fa-trash" href="javascript:void(0)"></a>
			<?= Html::hiddenInput("{$name}[value]", $item['value']); ?>
			<?= Html::hiddenInput("{$name}[url]", $item['url']); ?>
		</div></div>
		<?php endforeach ?>
	</div>
</div>
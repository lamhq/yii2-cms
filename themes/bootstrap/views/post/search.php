<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Search Results for: {text}', ['text' =>$term]);
$f = \Yii::$app->formatter;
?>
<?php $this->beginBlock('content-header'); ?>
<header class="content-header">
	<div class="container">
		<h1 class="page-title"><?= $f->asText($this->title) ?></h1>
	</div>
</header>
<?php $this->endBlock(); ?>

<?php echo \yii\widgets\ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_item',
    'summary'=>'',
    'pager'=>[ 'hideOnSinglePage'=>true,],
    'pager'=>[
    	'nextPageLabel'=>'<span class="glyphicon glyphicon-chevron-right"></span>',
    	'prevPageLabel'=>'<span class="glyphicon glyphicon-chevron-left"></span>',
    ]
])?>
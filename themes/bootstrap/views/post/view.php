<?php
/* @var $this yii\web\View */
/* @var $model app\model\Post */
use yii\widgets\Menu;
use yii\helpers\Html;
$f = \Yii::$app->formatter;
$this->title = $model->title;

?>
<?php $this->beginBlock('content-header'); ?>
<header class="content-header">
	<div class="container">
		<h1 class="page-title"><?= $f->asText($model->title) ?></h1>
	</div>
</header>
<?php $this->endBlock(); ?>

<article class="post type-post">
<header class="entry-header">
<div class="entry-meta">
	<p>
		<span class="posted-on"><span class="glyphicon glyphicon-calendar"></span>&nbsp; <?= $f->asDate($model->published_at) ?> </span>
		&nbsp;|&nbsp;
		<span class="by-line">
			<span class="glyphicon glyphicon-user"></span>&nbsp; 
			<span class="author vcard">
				<a rel="author" title="Posts by <?= $model->authorName ?>" href="#"><?= $model->authorName ?></a>
			</span>
		</span>
	</p>
</div>
<!-- .entry-meta -->
</header>
<!-- .entry-header -->
<div class="entry-content">
	<?= $f->asHtml($model->content) ?>
	<!-- .after-content -->

	<footer class="entry-meta">
	<span class="cat-links">
		<span class="glyphicon glyphicon-tag"></span>&nbsp; 

		<?= Html::a($model->category->name, $model->category->url) ?>
	</span>
	<span class="tags-links">
		<span class="glyphicon glyphicon-tags"></span> &nbsp;
		<?php
		$items = array_map(function ($tag) {
			return $tag->toMenuItem();
		}, $model->tags);
		?>
		<?= Menu::widget([
			'items'=>$items,
			'itemOptions'=>['tag'=>false],
			'options'=>['tag'=>false],
		]) ?>
	</footer>
	<!-- .entry-meta -->
</div>
<!-- .entry-content -->
</article>
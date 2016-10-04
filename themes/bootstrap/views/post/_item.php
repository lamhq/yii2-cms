<?php
/* @var $this yii\web\View */
$f = \Yii::$app->formatter;
?>
<article class="post">
	<header class="entry-header">
		<div class="entry-meta">
			<h1 class="entry-title"><a href="<?= $model->url ?>" rel="bookmark"><?= $f->asText($model->title) ?></a></h1>
			<p>
				<span class="posted-on"><span class="glyphicon glyphicon-calendar"></span>&nbsp; <?= $f->asDate($model->published_at) ?> </span>
				&nbsp;|&nbsp;
				<span class="by-line">
					<span class="glyphicon glyphicon-user"></span>&nbsp; 
					<span class="author vcard">
						<a href="#" title="Posts by <?= '#' ?>" rel="author"><?= $f->asText($model->authorName) ?></a>
					</span>
				</span>
			</p>
		</div>
	<!-- .entry-meta -->
	</header>
	<!-- .entry-header -->

	<div class="entry-summary">
		<p>
			<?= $f->asText($model->short_content) ?>
			&hellip; <a class="read-more" href="<?= $model->url ?>">Read More</a>
		</p>
		<hr>
	</div>
	<!-- .entry-summary -->
</article>
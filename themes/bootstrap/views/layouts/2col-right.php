<?php
/**
 * @var $this yii\web\View
 */
use yii\helpers\ArrayHelper;
use app\widgets\PostList;
use app\widgets\CategoryList;
use app\widgets\Search;
use app\widgets\TagCloud;
?>
<?php $this->beginContent('@webroot/themes/bootstrap/views/layouts/main.php'); ?>
<div id="main-grid" class="row">
	<section id="primary" class="content-area col-md-8">
		<main id="main" class="site-main" role="main">
			<?= $content ?>
		</main><!-- #main -->
	</section><!-- #primary -->

	<div id="secondary" class="widget-area col-md-4" role="complementary">
		<?= Search::widget() ?>
		<?= PostList::widget(['type'=>'recent']) ?>
		<?= CategoryList::widget() ?>
		<?= TagCloud::widget() ?>
	</div>
</div>
<?php $this->endContent(); ?>
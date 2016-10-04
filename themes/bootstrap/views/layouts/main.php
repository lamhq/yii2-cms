<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

app\assets\BootstrapThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<style>
	.custom-header-image {
		background-image: url('<?= Url::to('@web/themes/bootstrap/images/header.jpg'); ?>');
		width: 1600px;
		height: 200px;
	}
	body {
		background-image: url('<?= Url::to('@web/themes/bootstrap/images/background.jpg'); ?>');
		background-repeat: repeat;
		background-position: top left;
		background-attachment: scroll;
	}
	</style>
</head>
<body class="">
<?php $this->beginBody() ?>
<div id="page">
	<header id="masthead" class="site-header" role="banner">

		<div id="site-branding" class="site-branding">
			<div class="custom-header-image" style="">
				<div class="container">
					<div class="site-branding-text">
						<h1 class="site-title"><a href="<?= Yii::$app->homeUrl ?>" rel="home"><?= Yii::$app->name ?></a></h1>
						<h2 class="site-description">A simple CMS site</h2>
					</div>
				</div>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
		<div class="navbar navbar-default navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>" rel="home">A simple CMS site</a>
			</div>
			<!-- navbar-header -->
			<div class="navbar-collapse collapse">
				<div class="menu-testing-menu-container">
					<?= Nav::widget([
						'activateItems'=>false,
				        'options' => ['class' => 'nav navbar-nav'],
				        'items' => [
				            ['label' => 'Home', 'url' => Yii::$app->homeUrl ],
				            ['label' => 'About', 'url' => ['/site/about']],
				            ['label' => 'Contact', 'url' => ['/site/contact']],
				        ],
				    ]); ?>
				</div>
			</div>
			<!-- .container -->
		</div>
		</div>
		<!-- .navbar -->
		</nav>
	</header>

	<div id="content" class="site-content">
		<div class="container">
			<?= $content ?>
		</div>
	</div>
	
	<div id="sidebar-pagebottom" class="sidebar-pagebottom">
	<aside class="section bg-lightgreen text-center clearfix">
	<div class="container">
		<h2 class="widget-title">THIS IS A CALL TO ACTION AREA</h2>
		<div class="textwidget">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<p>
						This is just an example shown for the theme preview. You can put whatever you'd like.
					</p>
					<p>
						<button type="button" class="btn btn-hollow btn-lg">Call To Action Button</button>
					</p>
				</div>
				<!-- col-lg-8 -->
			</div>
			<!-- row -->
		</div>
		<!-- textwidget -->
	</div>
	<!-- container -->
	</aside>
</div>
<!-- .sidebar-pagebottom -->
<footer id="colophon" class="site-footer" role="contentinfo">
<div class="after-footer">
	<div class="container">
		<div class="footer-nav-menu pull-left">
			<nav id="footer-navigation" class="secondary-navigation" role="navigation">
			<h1 class="menu-toggle sr-only">Footer Menu</h1>
			<div class="sample-menu-footer-container">
				<ul class="list-inline dividers">
					<li><a class="smoothscroll" title="Back to top of page" href="#page"><span class="fa fa-angle-up"></span> Top</a></li>
					<li><a title="Home" href="<?= Yii::$app->homeUrl ?>">Home</a></li>
				</ul>
			</div>
			</nav>
		</div>
		<!-- .footer-nav-menu -->
		<div id="site-credits" class="site-credits pull-right">
			<span class="credits-copyright">&copy; 2016 <a href="<?= Yii::$app->homeUrl ?>" rel="home"><?= Yii::$app->name ?></a>. </span><span class="credits-theme"> by <a href="http://lamhq.com" rel="profile" target="_blank">lamhq</a>.</span>
		</div>
		<!-- .site-credits -->
	</div>
	<!-- .container -->
</div>
<!-- .after-footer -->
</footer>
<!-- #colophon -->
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

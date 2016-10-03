<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
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
</head>
<body class="home blog custom-background post-layout-small">
<?php $this->beginBody() ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
	<header id="masthead" class="site-header clearfix" role="banner">
	<div class="header-main container clearfix">
		<div id="logo" class="site-branding clearfix">
			<h1 class="site-title"><a href="http://localhost/wp/" rel="home">Wordpress Demo</a></h1>
		</div>
		<!-- .site-branding -->
		<nav id="main-navigation" class="primary-navigation navigation clearfix" role="navigation">
		<ul id="menu-all-pages" class="main-navigation-menu">
			<li id="menu-item-1636" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1636"><a href="http://wpthemetestdata.wordpress.com/">Home</a></li>
			<li id="menu-item-1637" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1637"><a href="http://localhost/wp/blog/">Blog</a></li>
			<li id="menu-item-1638" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1638"><a href="http://localhost/wp/front-page/">Front Page</a></li>
			<li id="menu-item-1639" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1639"><a href="http://localhost/wp/about/">About The Tests</a>
			<ul class="sub-menu">
				<li id="menu-item-1697" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1697"><a href="http://localhost/wp/about/page-image-alignment/">Page Image Alignment</a></li>
				<li id="menu-item-1698" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1698"><a href="http://localhost/wp/about/page-markup-and-formatting/">Page Markup And Formatting</a></li>
				<li id="menu-item-1640" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1640"><a href="http://localhost/wp/about/clearing-floats/">Clearing Floats</a></li>
				<li id="menu-item-1641" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1641"><a href="http://localhost/wp/about/page-with-comments/">Page with comments</a></li>
				<li id="menu-item-1642" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1642"><a href="http://localhost/wp/about/page-with-comments-disabled/">Page with comments disabled</a></li>
			</ul>
			</li>
			<li id="menu-item-1643" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1643"><a href="http://localhost/wp/level-1/">Level 1</a>
			<ul class="sub-menu">
				<li id="menu-item-1644" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1644"><a href="http://localhost/wp/level-1/level-2/">Level 2</a>
				<ul class="sub-menu">
					<li id="menu-item-1645" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1645"><a href="http://localhost/wp/level-1/level-2/level-3/">Level 3</a></li>
					<li id="menu-item-1699" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1699"><a href="http://localhost/wp/level-1/level-2/level-3a/">Level 3a</a></li>
					<li id="menu-item-1700" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1700"><a href="http://localhost/wp/level-1/level-2/level-3b/">Level 3b</a></li>
				</ul>
				</li>
				<li id="menu-item-1701" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1701"><a href="http://localhost/wp/level-1/level-2a/">Level 2a</a></li>
				<li id="menu-item-1702" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1702"><a href="http://localhost/wp/level-1/level-2b/">Level 2b</a></li>
			</ul>
			</li>
			<li id="menu-item-1646" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1646"><a href="http://localhost/wp/lorem-ipsum/">Lorem Ipsum</a></li>
			<li id="menu-item-1703" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1703"><a href="http://localhost/wp/page-a/">Page A</a></li>
			<li id="menu-item-1704" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1704"><a href="http://localhost/wp/page-b/">Page B</a></li>
		</ul>
		</nav>
		<!-- #main-navigation -->
	</div>
	<!-- .header-main -->
	</header>
	<!-- #masthead -->
	<div id="headimg" class="header-image">
		<img src="http://localhost/wp/wp-content/uploads/2016/10/cropped-header.png" srcset="http://localhost/wp/wp-content/uploads/2016/10/cropped-header.png 1920w, http://localhost/wp/wp-content/uploads/2016/10/cropped-header-300x75.png 300w, http://localhost/wp/wp-content/uploads/2016/10/cropped-header-768x192.png 768w, http://localhost/wp/wp-content/uploads/2016/10/cropped-header-1024x256.png 1024w" width="1920" height="480" alt="Wordpress Demo">
	</div>
	<?= $content ?>
	<div id="footer" class="footer-wrap">
		<footer id="colophon" class="site-footer container clearfix" role="contentinfo">
		<div id="footer-text" class="site-info">
			<span class="credit-link">
			Powered by <a href="http://wordpress.org" title="WordPress">WordPress</a> and <a href="https://themezee.com/themes/poseidon/" title="Poseidon WordPress Theme">Poseidon</a>. </span>
		</div>
		<!-- .site-info -->
		</footer>
		<!-- #colophon -->
	</div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

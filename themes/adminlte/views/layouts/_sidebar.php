<?php
/**
 * @var $this yii\web\View
 */
use backend\widgets\AdminLteMenu;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<?= AdminLteMenu::widget([
			'items'=>[
				['label'=>Yii::t('app', 'Banner'), 'url'=>['/backend/banner'], 'icon'=>'fa fa-circle-o'],
				['label'=>Yii::t('app', 'Post'), 'url'=>['/backend/post'], 'icon'=>'fa fa-circle-o'],
				['label'=>Yii::t('app', 'Page'), 'url'=>['/backend/page'], 'icon'=>'fa fa-circle-o'],
				['label'=>Yii::t('app', 'Category'), 'url'=>['/backend/category'], 'icon'=>'fa fa-circle-o'],
			]
		]) ?>
	</section>
	<!-- /.sidebar -->
</aside>


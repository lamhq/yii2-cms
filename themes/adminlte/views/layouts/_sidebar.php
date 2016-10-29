<?php
/**
 * @var $this yii\web\View
 */
use backend\widgets\AdminLteMenu;
use backend\widgets\AuthBlock;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<?= AdminLteMenu::widget([
			'items'=>[
				['label'=>Yii::t('app', 'Content'), 'icon'=>'fa fa-edit', 'items'=>[
					['label'=>Yii::t('app', 'Static Page'), 'url'=>['/backend/page/index'], 'permission'=>'managePage' ],
					['label'=>Yii::t('app', 'Email Template'), 'url'=>['/backend/email-template/index'], 'permission'=>'manageEmailTemplate' ],
					['label'=>Yii::t('app', 'Post'), 'url'=>['/backend/post/index'], 'permission'=>'viewPost', 'permission'=>'managePost' ],
					['label'=>Yii::t('app', 'Category'), 'url'=>['/backend/category/index'], 'permission'=>'manageCategory' ],
					['label'=>Yii::t('app', 'Slideshow'), 'url'=>['/backend/slideshow/index'], 'permission'=>'manageSlideshow' ],
				]],
				['label'=>Yii::t('app', 'System'), 'icon'=>'fa fa-cogs', 'items'=>[
					['label'=>Yii::t('app', 'Configuration'), 'url'=>['/backend/setting/index'], 'icon'=>'fa fa-wrench', 'permission'=>'configuration' ],
					['label'=>Yii::t('app', 'User'), 'url'=>['/backend/account/index'], 'icon'=>'fa fa-user', 'permission'=>'manageAccount' ],
					['label'=>Yii::t('app', 'Role'), 'url'=>['/backend/role/index'], 'icon'=>'fa fa-group', 'permission'=>'manageRole' ],
					['label'=>Yii::t('app', 'Permission'), 'url'=>['/backend/permission/index'], 'icon'=>'fa fa-key', 'permission'=>'managePermission' ],
				]],
			]
		]) ?>
	</section>
	<!-- /.sidebar -->
</aside>

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
				['label'=>Yii::t('app', 'Content'), 'icon'=>'fa fa-edit', 'items'=>[
					['label'=>Yii::t('app', 'Static Page'), 'url'=>['/backend/page/index'] ],
					['label'=>Yii::t('app', 'Email Template'), 'url'=>['/backend/email-template/index'] ],
					['label'=>Yii::t('app', 'Banner'), 'url'=>['/backend/banner/index'] ],
					['label'=>Yii::t('app', 'Post'), 'url'=>['/backend/post/index'] ],
					['label'=>Yii::t('app', 'Category'), 'url'=>['/backend/category/index'] ],
				]],
				['label'=>Yii::t('app', 'System'), 'icon'=>'fa fa-cogs', 'items'=>[
					['label'=>Yii::t('app', 'Configuration'), 'url'=>['/backend/setting/index'], 'icon'=>'fa fa-wrench' ],
					['label'=>Yii::t('app', 'User'), 'url'=>['/backend/account/index'], 'icon'=>'fa fa-user' ],
					['label'=>Yii::t('app', 'Role'), 'url'=>['/backend/role/index'], 'icon'=>'fa fa-group' ],
					['label'=>Yii::t('app', 'Permission'), 'url'=>['/backend/permission/index'], 'icon'=>'fa fa-group' ],
				]],
			]
		]) ?>
	</section>
	<!-- /.sidebar -->
</aside>


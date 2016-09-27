<?php
/**
 * @var $this yii\web\View
 */
use yii\helpers\Html;

?>
<!-- header logo: style can be found in header.less -->
<header class="main-header">
    <a href="<?= Yii::$app->homeUrl ?>" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <?= Yii::$app->name ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?= Yii::t('app', 'Toggle navigation') ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?= Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(Yii::t('app', 'Account'), ['/backend/site/account'], [
									'class'=>'btn btn-default btn-flat',
								]) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(Yii::t('app', 'Logout'), ['/backend/site/logout'], [
									'class'=>'btn btn-default btn-flat', 
									 'data-method'=>'post'
								]) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

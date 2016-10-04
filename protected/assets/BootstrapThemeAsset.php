<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapThemeAsset extends AssetBundle
{
    public $baseUrl = '@web/themes/bootstrap';
    public $css = [
        'css/theme-base.css',
        'css/theme-flat.css',
        'css/style.css',
        '//fonts.googleapis.com/css?family=Lato:300,400,700|Raleway:400,300,700',
    ];
    public $js = [
        'js/theme.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'sersid\fontawesome\Asset',
    ];
}

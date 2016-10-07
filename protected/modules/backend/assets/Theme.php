<?php
namespace backend\assets;

use yii\web\AssetBundle;

class Theme extends AssetBundle
{
	public $baseUrl = '@web/themes/adminlte';
	
	public $css = [
		'css/style.css'
	];
	
	public $js = [
		'js/common.js',
		'js/app.js',
	];

	public $depends = [
		'lamhq\yii2\asset\AdminLte',
	];
}

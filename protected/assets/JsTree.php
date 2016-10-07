<?php
namespace app\assets;

use yii\web\AssetBundle;

class JsTree extends AssetBundle
{
	public $sourcePath = '@bower/jstree/dist';
	
	public $css = [
		'themes/default/style.min.css'
	];
	
	public $js = [
		'jstree.min.js',
	];

	public $depends = [
		'yii\web\JqueryAsset'
	];
}

<?php
namespace app\assets;

use yii\web\AssetBundle;

class TreeCheckbox extends AssetBundle
{
	public $sourcePath = '@app/assets/tree-checkbox';
	
	public $css = [
	];
	
	public $js = [
		'script.js',
	];

	public $depends = [
		'app\assets\JsTree'
	];
}

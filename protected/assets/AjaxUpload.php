<?php
namespace app\assets;

use yii\web\AssetBundle;

class AjaxUpload extends AssetBundle
{
	public $sourcePath = '@app/assets/ajax-upload';
	
	public $css = [
		'style.css'
	];
	
	public $js = [
		'au.js',
	];

	public $depends = [
        'yii\web\JqueryAsset',
        '\yii\jui\JuiAsset',	// require sortable plugin
        'sersid\fontawesome\Asset',
	];
}

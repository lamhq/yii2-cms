<?php

$config = \yii\helpers\ArrayHelper::merge(require(__DIR__ . '/_common.php'), [
	'layout'=>'2col-right',
	'defaultRoute'=>'post',
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'oHnhfGp-3LXPUG9eEe9_jlgxYWchmzif',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'view' => [
			'theme' => [
				'basePath' => '@webroot/themes/bootstrap',
				'baseUrl' => '@web/themes/bootstrap',
				'pathMap' => [
					'@app/views' => '@webroot/themes/bootstrap/views',
					'@app/widgets/views' => '@webroot/themes/bootstrap/views/widgets',
					'@app/modules/views' => '@webroot/themes/bootstrap/views/modules',
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'post/<slug:.*>.html' => '/post/view',
			],
		],
		'assetManager' => [
			'hashCallback' => function ($path) {
				// make user friendly path
				$s2 = basename($path);
				$s1 = basename(dirname($path));
				return "$s1-$s2";
			}
		],
	],
]);

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;

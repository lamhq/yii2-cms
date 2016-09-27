<?php
// common shared configuration parameters and values between web app and console app
define('DS', DIRECTORY_SEPARATOR);
Yii::setAlias('@setup', realpath(__DIR__.'/../modules/setup'));
Yii::setAlias('@backend', realpath(__DIR__.'/../modules/backend'));

$config = [
	'id' => 'yii2-core',
	'name' => 'Yii2 Core Project',
	'vendorPath' => realpath(__DIR__ . '/../../vendor'),
	'basePath' => dirname(__DIR__),
	'timeZone' => 'Asia/Bangkok',
	'language' => 'en-US',
	'sourceLanguage' => 'en-US',
	'bootstrap' => ['log'],

	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'log' => [
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=localhost;dbname=yii2',
			'tablePrefix' => 'yii2_',
			'username' => 'root',
			'password' => '',
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],        
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => false,
		],
	],
	'modules' => [
		'setup' => [ 'class' => 'setup\Module' ],
		'backend' => [ 'class' => 'backend\Module' ],
	],
	'params' => [
		'adminEmail' => 'admin@example.com',
        'robotEmail' => 'noreply@m.mm',
		'accessRules'=>[
			[
				'allow' => true,
				'controllers' => ['backend/site'],
				'actions' => ['login', 'error', 'forgot-password', 'reset-password'],
				'roles' => ['?'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
			[
				'allow' => false,
				'roles' => ['?'],
			],
		]
	],
];

return $config;

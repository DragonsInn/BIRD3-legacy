<?php

// Some short-hands:
$CDN = "http://cdn.dragonsinn.tk";
$base = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';

Yii::setPathOfAlias('cdn',$base.'/cdn');

return array(
	'basePath'=>$base."/protected",
	'name'=>'BIRD3',
	'theme'=>'dragonsinn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'themeManager'=>array(
			'basePath'=>$base."/cdn/themes",
			'baseUrl'=>"/cdn/themes"
		),
		'assetManager'=>array(
			'basePath'=>$base."/cdn/assets",
			'baseUrl'=>"/cdn/assets"
		),
		'cdn'=>array(
			'class'=>'CDNHelper',
			'basePath'=>$base.'/cdn',
			'baseUrl'=>'/cdn'
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=dragonsinn_tk',
			'emulatePrepare' => true,
			'username' => 'dragonsinn',
			'password' => 'icwrnyp40icwr',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'version'=>$_SERVER["HTTP_BIRD3_VERSION"] || "BIRD 3.0-dev"
	),
);

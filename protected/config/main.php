<?php

// Some short-hands:
$CDN = "http://cdn.dragonsinn.tk";
$base = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."..";

Yii::setPathOfAlias('cdn',$base.'/cdn');

$BIRD3 = parse_ini_file($base."/config/BIRD3.ini", true);

$config = array(
	'basePath'=>$base."/protected",
	'runtimePath'=>$base."/cache",
	'name'=>'BIRD3',
	'theme'=>'dragonsinn',

	// preloading 'log' component
	'preload'=>array('log', 'user'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.user.components.*',
		'application.modules.user.models.*',
	),

	'modules'=>array(
		"user"
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'BIRD3User',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'themeManager'=>array(
			'basePath'=>$base."/cdn/themes",
			'baseUrl'=>$BIRD3['CDN']['baseUrl']."/themes"
		),
		'assetManager'=>array(
			'basePath'=>$base."/cdn/assets",
			'baseUrl'=>$BIRD3['CDN']['baseUrl']."/assets"
		),
		'cdn'=>array(
			'class'=>'CDNHelper',
			'basePath'=>$base.'/cdn',
			'baseUrl'=>$BIRD3['CDN']['baseUrl']
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname='.$BIRD3['DB']['mydb'],
			'emulatePrepare' => true,
			'username' => $BIRD3['DB']['user'],
			'password' => $BIRD3['DB']['pass'],
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_'
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
		'version'=>$BIRD3["BIRD3"]["version"]
	),
);

// Extra work
if($BIRD3["Yii"]["use_path_urls"]=="1") {
	$config['components']['urlManager'] = array(
		'urlFormat'=>'path',
		'rules'=>array(
			'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		),
	);
}

return $config;

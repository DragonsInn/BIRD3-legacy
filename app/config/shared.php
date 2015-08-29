<?php

// Some short-hands:
$base = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."..";

# Some aliases
Yii::setPathOfAlias('cdn',$base.'/cdn');
Yii::setPathOfAlias('composer',$base.'/php_modules');
Yii::setPathOfAlias('bower',$base.'/bower_components');
Yii::setPathOfAlias('npm',$base.'/node_modules');
Yii::setPathOfAlias('cache',$base.'/cache');

if(!isset($_SERVER["SERVER_VERSION"])) {
    $package = file_get_contents("$base/package.json");
    $json = json_decode($package);
    $_SERVER["SERVER_VERSION"] = $json->version;
}

$BIRD3 = parse_ini_file($base."/config/BIRD3.ini", true);
$version = $_SERVER["SERVER_SOFTWARE"];

$CDN = "http://".$BIRD3["CDN"]["url"];

return array(
	'basePath'=>$base."/app",
	'runtimePath'=>$base."/cache",
	'name'=>'BIRD3',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		# User
		'application.modules.user.components.*',
		'application.modules.user.models.*',
		# Characters
		'application.modules.characters.components.*',
		'application.modules.characters.models.*',
		# Caching
		'ext.redis.*',
		# Misc
		'ext.easyimage.*',
		'ext.BIRD3.components.*'
	),

	'modules'=>array(
		"user",
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'BIRD3User',
			'allowAutoLogin'=>true,
			'autoRenewCookie'=>true,
		),
		'cleanTalk'=>array(
            'class'=>'ext.yii-antispam.CleanTalkApi',
            'apiKey'=>$BIRD3["API"]["cleantalk.key"],
        ),
		'assetManager'=>array(
			'basePath'=>$base."/cdn/assets",
			'baseUrl'=>$BIRD3['CDN']['baseUrl']."/assets",
		),
		'themeManager'=>array(
			'basePath'=>$base."/themes",
			"baseUrl"=>"/themes"
		),
		'cdn'=>array(
			'class'=>'CDNHelper',
			'basePath'=>$base.'/cdn',
			'baseUrl'=>$BIRD3['CDN']['baseUrl']
		),
		'clientCache'=>array(
			'class'=>'ClientCache'
		),
		'contentCompactor' => array(
			'class' => 'ext.contentCompactor.ContentCompactor',
			'options' => array(
				'compress_css' => !isset($_GET["dev"]), // Compress CSS
				'strip_comments' => !isset($_GET["dev"]), // Remove comments
				'keep_conditional_comments' => !isset($_GET["dev"]), // Remove conditional comments
				'compress_horizontal' => !isset($_GET["dev"]), // Compress horizontally
				'compress_vertical' => !isset($_GET["dev"]), // Compress vertically
				'compress_scripts' => !isset($_GET["dev"]), // Compress inline scripts using basic algorithm
				'line_break' => PHP_EOL, // The type of rowbreak you use in your document
				'preserved_tags' => array('textarea', 'pre', 'script', 'style', 'code', "p"),
				'preserved_boundry' => '@@PRESERVEDTAG@@',
				'conditional_boundries' => array('@@IECOND-OPEN@@', '@@IECOND-CLOSE@@'),
				'script_compression_callback' => false,
				'script_compression_callback_args' => array(),
			)
		),
		'browser'=>array(
			'class'=>'Browser'
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname='.$BIRD3['DB']['mydb'],
			'emulatePrepare' => true,
			'username' => $BIRD3['DB']['user'],
			'password' => $BIRD3['DB']['pass'],
			'charset' => 'utf8',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				# Non-default
				'/u/<name>'=>'/user/profile/view',
				'/user/<action:\w+>'=>'/user/user/<action>',
				'/docs/<name:\w+>'=>'/docs/show/name/<name>',
				# Default
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		"redis" => array(
        	"class" => "ext.redis.ARedisConnection",
        	"hostname" => "localhost",
        	"port" => 6379,
			"prefix" => "", # Empty
			"database" => 0 #To match nodejs.
    	),
		'cache'=>array(
			'class'=>'ext.redis.ARedisCache',
		),
		'easyImage' => array(
			'class' => 'application.extensions.easyimage.EasyImage',
			//'driver' => 'GD',
			//'quality' => 100,
			'cachePath' => "$base/cdn/content/images/"
			//'cacheTime' => 2592000,
			//'retinaSupport' => false,
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
		'adminEmail'=>'ingwie2000@googlemail.com',
		'version'=>$version
	),
);

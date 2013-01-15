<?php

require_once("globals.php");

$_config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'NRW Haushalt',
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.extensions.*',
	),
	'defaultController'=>'site',
    'modules'=>array(
    ),
	'components'=>array(
		/*'kint' => array(
		    'class' => 'ext.Kint.Kint',
		),*/
		'user'=>array(
			'allowAutoLogin'=>true,
		),
   		/*'cache' => array(
    		'class' => 'system.caching.CApcCache',
		),*/
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'site/captcha'=>'site/captcha',
				'admin/<code>'=>'site/admin',
				'admin/<code>/<action>/<id>'=>'site/admin',
				'feedback/<year>/<typ>/<entry>'=>'site/feedback',
				'<year>/<typ>'=>'site/budget',
				'<year>/<typ>/<entry>'=>'site/budget',
				'<year>/<typ>/<entry>/<error>'=>'site/budget',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				/*array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace, info, profile',
				),*/
				
			),
		),
	),
	'params'=>require(dirname(__FILE__).'/params.php'),
);

include("custom.php");

return $_config;

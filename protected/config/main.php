<?php

require_once("globals.php");

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'NRW Haushalt',
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.extensions.*',
		//'application.extensions.SpreadsheetReader.*',
	),
	'defaultController'=>'site',
    'modules'=>array(
		/*'importcsv'=>array(
            'path'=>'upload/',
        ),*/
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
		/*'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),*/
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=haushalt_neu',
			'username' => 'haushalt_neu',
			'password' => 'haushalt_neu',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'test/<action>'=>'test/<action>',
				'<year>/<typ>'=>'site/budget',
				'<year>/<typ>/<entry>'=>'site/budget',
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
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace, info, profile',
				),
				
			),
		),
	),
	'params'=>require(dirname(__FILE__).'/params.php'),
);

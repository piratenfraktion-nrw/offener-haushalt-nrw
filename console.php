<?php

defined('YII_DEBUG') or define('YII_DEBUG',true);

// include Yii bootstrap file
require_once('framework/yii.php');

// create application instance and run
$config=dirname(__FILE__).'/protected/config/console.php';
Yii::createConsoleApplication($config)->run();

?>

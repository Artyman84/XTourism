<?php

$exp = explode('?', $_SERVER['REQUEST_URI']);
if (substr($exp[0], -1) !== '/') {
    $exp[0] .= '/';
}
$_SERVER['REQUEST_URI'] = implode('?', $exp);

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/backend.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

ini_set('display_errors', 'off');

if( YII_DEBUG ) {
    error_reporting(E_ALL);
}

Yii::createWebApplication($config)->runEnd('backend');

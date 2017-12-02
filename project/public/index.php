<?php

date_default_timezone_set("Europe/Paris");

require_once(dirname(__DIR__).'/app/AutoLoaderClass.php');
$conf = include(dirname(__DIR__).'/private/configuration.php');
$service = include(dirname(__DIR__).'/private/service.php');
$route = include(dirname(__DIR__).'/private/route.php');

use Application\AutoLoaderClass;
use Application\Application;

$loader = new AutoLoaderClass();
$loader->addNamespace('Application', dirname(__DIR__).'/app');
$loader->addNamespace('', dirname(__DIR__).'/src');
$loader->register();

$app = new Application($conf, $service, $route);
$app->init();
return $app->start();

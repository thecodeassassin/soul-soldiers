<?php

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

define('BASE_URL', null);

 
$di = new Phalcon\DI\FactoryDefault();
include __DIR__ . '/../app/config/services/loader.php';

$assetsConfig = include __DIR__ . "/../app/config/assetsConfig.php";
$config = include __DIR__ . "/../app/config/config.php";

$di->setShared('config', function() use ($config) {
    return $config;
});


// load the db service
include __DIR__ . "/../app/config/services/db.php";

$isIntranet = getenv("INTRANET") === "true";

if ($isIntranet) {
    define('ACTIVE_MODULE', 'intranet');
} else {
    define('ACTIVE_MODULE', 'website');
}

$assetManager = new \Soul\Assets\Manager($di, $assetsConfig[ACTIVE_MODULE]);
$assetManager->outputCss('main');
$assetManager->outputJs('scripts');

$event = \Soul\Model\Event::getCurrent();

if ($event) {
    $event->getSeatMap();
}

echo "Generated assets";
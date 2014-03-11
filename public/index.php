<?php


// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

// Define application environment
// Change 'development' to 'production' once the application is up and running on the production site
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('BASE_URL')
|| define('BASE_URL', sprintf('%s://%s', strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https')
    === false ? 'http' : 'https', $_SERVER['HTTP_HOST']));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path()
)));

if (!is_readable(APPLICATION_PATH . '/config/config.php')) {
    die('Fatal: No config file found');
}

if (!is_readable(APPLICATION_PATH . '/../vendor/autoload.php')) {
    die('Fatal: Please run wget https://getcomposer.org/installer && php installer && php composer.phar install');
}

// create the logfile if it doesn't exist
$logFile = APPLICATION_PATH . '/log/'.APPLICATION_ENV.'.log';
if (!file_exists($logFile)) {

    if (!is_writeable(dirname($logFile))) {
        die('Please chmod 777 '.dirname($logFile));
    }

    touch($logFile);
}

/*
 * Read the configuration
 */
$config = include __DIR__ . "/../app/config/config.php";
$configDist = include __DIR__ . "/../app/config/config.dist.php";

if ($config->count() != $configDist->count()) {
    die('Fatal: It seems that the configuration file does not contain all the requirements set by the config.dist.php');
}

/**
 * Read services
 */
include __DIR__ . "/../app/config/services.php";


if (APPLICATION_ENV == 'development') {
    ini_set('display_errors', 1);
    (new Phalcon\Debug)->listen();

    // load the default kernel in development mode
    $kernel = new \Phalcon\Mvc\Application($di);

} else {
    $kernel = new \Soul\Kernel\Application($di);
}

/**
 * Handle the request
 */


echo $kernel->handle()->getContent();
// exception/404 managed from the dispatcher event



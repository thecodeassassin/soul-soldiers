<?php
/**
 * @author Stephen Hoogendijk
 */

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir
    )
);

$loader->register();

// autoload the dependencies found in composer
include __DIR__ . "/../../vendor/autoload.php";

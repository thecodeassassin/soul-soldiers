<?php
use Soul\Kernel;

$di->set('cache', function() use ($config){

    try {
        //Cache data for one day by default
        $backCache = new \Phalcon\Cache\Frontend\Data(array(
            "lifetime" => 86400
        ));

        $cache = new Phalcon\Cache\Backend\File($backCache, array(
            "cacheDir" => $config->application->cacheDir,
            "prefix" => "Site"
        ));

        return $cache;
    } catch(\Exception $e) {
        die(sprintf('Cannot connect to memcache server: %s', $e->getMessage()));
    }
});

//Set the views cache service
$di->set('viewCache', function() use ($config){

    //Cache data for one day by default
    $frontCache = new Phalcon\Cache\Frontend\Output(array(
        "lifetime" => 2592000
    ));

    //File backend settings
    $cache = new Phalcon\Cache\Backend\File($frontCache, array(
        "cacheDir" => $config->application->cacheDir,
        "prefix" => "Site"
    ));

    return $cache;
});

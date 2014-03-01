<?php

use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

$di->set('router', function() use ($config){

    $router = new \Phalcon\Mvc\Router();
    $routerConfig = include __DIR__ . "/../routerConfig.php";

    foreach ($routerConfig as $pattern => $paths) {
        $router->add($pattern, $paths);
    }

    $router->removeExtraSlashes(true);

    return $router;
});

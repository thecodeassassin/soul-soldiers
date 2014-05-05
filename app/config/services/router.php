<?php

use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

$di->set('router', function() use ($config){

    $router = new \Phalcon\Mvc\Router();

    $routerConfig = include __DIR__ . "/../routerConfig.php";
    $module = ACTIVE_MODULE;

    $router->setDefaultModule($module);

    if (!array_key_exists($module, $routerConfig)) {
        throw new \Exception(sprintf('Module %s has no routerconfig!', $module));
    }

    foreach ($routerConfig[$module] as $pattern => $paths) {
        $router->add($pattern, $paths);
    }

    $router->removeExtraSlashes(true);

    return $router;
});

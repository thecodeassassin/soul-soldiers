<?php

$di->set('router', function() use ($config){

    $router = new \Phalcon\Mvc\Router();

    $routerConfig = include __DIR__ . "/../routerConfig.php";
    $module = ACTIVE_MODULE;

    if (!array_key_exists($module, $routerConfig)) {
        throw new \Exception(sprintf('Module %s has no routerconfig!', $module));
    }

    if (array_key_exists('general', $routerConfig)) {
        $routerConfig[$module] = array_merge($routerConfig[$module], $routerConfig['general']);
    }

    foreach ($routerConfig[$module] as $pattern => $paths) {
        $router->add($pattern, $paths);
    }

    $router->removeExtraSlashes(true);

    return $router;
});

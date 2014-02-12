<?php

use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

$di->set('router', function() use ($config){
    return include __DIR__ . "/../router.php";
});

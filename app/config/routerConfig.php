<?php
$router = new \Phalcon\Mvc\Router();

// Custom routes

$router->add(
    "/login",
    [
        "controller" => "account",
        "action"     => "login"
    ]
);

$router->add(
    "/register",
    [
        "controller" => "account",
        "action"     => "register"
    ]
);

$router->removeExtraSlashes(true);
return $router;

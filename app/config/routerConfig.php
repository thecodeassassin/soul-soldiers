<?php

// Custom routes
return [
    '/login' => [
        "controller" => "account",
        "action"     => "login"
    ],

    '/logout' => [
        "controller" => 'account',
        "action"     => 'logout'
    ],

    '/register' =>  [
        "controller" => "account",
        "action"     => "register"
    ],

    '/home' => [
        "controller" => "index",
        "action" => "index"
    ]
];

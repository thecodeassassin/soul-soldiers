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

    '/forgot-password' =>  [
        "controller" => "account",
        "action"     => "forgotPassword"
    ],

    '/change-password/(.*)' =>  [
        "controller" => "account",
        "action"     => "changePassword"
    ],

    '/home' => [
        "controller" => "index",
        "action" => "index"
    ],

    '/confirm-user/(.*)' => [
        "controller" => "account",
        "action" => "confirmUser",
        "confirmKey" => 1
    ],

    '/resend-confirmation/(.*)' => [
        "controller" => "account",
        "action" => "resendConfirmation",
        "userId" => 1
    ]
];

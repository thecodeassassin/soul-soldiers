<?php

// Custom routes
return [

    /**
     * General
     */

    '/home' => [
        "controller" => "index",
        "action" => "index"
    ],

    /**
     * Account
     */

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
        "action"     => "changePassword",
        "confirmKey" => 1
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
    ],

    /**
     * Event
     */
    '/event/([a-zA-Z0-9_-]+)' => [
        "controller" => "event",
        "action" => "show",
        "systemName" => 1
    ],

    '/event/register/([a-zA-Z0-9_-]+)' => [
        "controller" => "event",
        "action" => "register",
        "systemName" => 1
    ]
];

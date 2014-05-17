<?php

return [


    /**
     * General routes
     */
    'general' => [

        /**
         * Content
         */

        '/static/(.*)' => [
            "controller" => "static",
            "action"     => "index",
            "resource" => 1
        ],

        '/home' => [
            "controller" => "index",
            "action" => "index"
        ],

        '/content/(.*)' => [
            "controller" => "content",
            "action"     => "show",
            "name" => 1
        ],

        '/news/add' => [
            "controller" => "index",
            "action"     => "addNews"
        ],

        '/news/delete/([0-9])' => [
            "controller" => "index",
            "action"     => "deleteNews",
            "newsId" => 1
        ],

        '/news/edit' => [
            "controller" => "index",
            "action"     => "editNews"
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

        '/forgot-password' =>  [
            "controller" => "account",
            "action"     => "forgotPassword"
        ],


    ],

    /**
     * Website routing
     */

    'website' => [

        /**
         * General
         */

        '/terms' => [
            "controller" => "content",
            "action"     => "show",
            "name" => 'rules'
        ],

        /**
         * Account
         */

        '/register' =>  [
            "controller" => "account",
            "action"     => "register"
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
    ],

    /**
     * Intranet routing
     */

    'intranet' => [

        '/home' => [
            "controller" => "index",
            "action" => "index"
        ],

        '/tournaments' => [
            "controller" => "tournament",
            "action" => "index"
        ],

        '/tournament/signup/([a-zA-Z0-9_-]+)' => [
            "controller" => "tournament",
            "action" => 'signup',
            "systemName" => 1
        ]
    ]

];

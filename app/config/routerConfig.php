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

        '/news' => [
            "controller" => "index",
            "action"     => "news"
        ],

        '/news/add' => [
            "controller" => "index",
            "action"     => "addNews"
        ],

        '/news/delete/(.*)' => [
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

        '/archive/([a-zA-Z0-9_-]+)' => [
            "controller" => "archive",
            "action" => "index",
            "systemName" => 1
        ],

        '/event/register/([a-zA-Z0-9_-]+)' => [
            "controller" => "event",
            "action" => "register",
            "systemName" => 1
        ],

        '/event/([a-zA-Z0-9_-]+)/reserve-seat/([0-9\.]+)' => [
            "controller" => "event",
            "action" => "reserveSeat",
            "systemName" => 1,
            "seat" => 2
        ],


        /*
         * Forum
         */
//        '/forum' => [
//            'controller' => 'forum',
//            'action' => 'index'
//        ]
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
        ],

        '/tournament/score/add/([0-9]+)' => [
            "controller" => "tournament",
            "action" => 'addScore',
            "userId" => 1
        ],

        '/tournament/remove/([0-9]+)' => [
            "controller" => "tournament",
            "action" => 'removeUser',
            "userId" => 1
        ],

        '/tournament/start/([0-9]+)' => [
            "controller" => "tournament",
            "action" => 'start',
            "systemName" => 1
        ],

        '/tournament/end/([0-9]+)' => [
            "controller" => "tournament",
            "action" => 'end',
            "systemName" => 1
        ],

        '/tournament/overview/([0-9]+)' => [
            "controller" => "tournament",
            "action" => 'overview',
            "systemName" => 1
        ],

        // Admin specific routes
        '/admin/tournaments/manage/([a-zA-Z0-9_-]+)' => [
            "controller" => "admin",
            "action" => 'manageTournament',
            "systemName" => 1
        ],

        '/admin/tournaments/delete/([a-zA-Z0-9_-]+)' => [
            "controller" => "admin",
            "action" => 'deleteTournament',
            "systemName" => 1
        ],

        '/admin/tournaments/generate-players/([a-zA-Z0-9_-]+)/([0-9]+)' => [
            "controller" => "admin",
            "action" => 'generatePlayers',
            "systemName" => 1,
            "count" => 2

        ],

        '/admin/tournaments/add' => [
            "controller" => "admin",
            "action" => 'addTournament'
        ],
    ]

];

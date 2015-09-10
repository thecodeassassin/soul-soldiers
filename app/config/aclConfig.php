<?php
use \Soul\AclBuilder as AclBuilder;

/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @namespace Soul
 */
return new \Phalcon\Config(
    [

        /**
         * Website ACL
         */
        'website' => [

            /**
             * Roles should be sorted in order of rights
             */
            'roles' => [
                AclBuilder::ROLE_ADMIN,
                AclBuilder::ROLE_MODERATOR,
                AclBuilder::ROLE_USER,
                AclBuilder::ROLE_GUEST
            ],
            /**
             * Public controllers are controllers available for ALL roles.
             * If only a certain role is allowed to access a action
             * within a public controller, then the action can be protected by adding it to the role.
             * Then only that role (and it's children) will inherit the rights to access it.
             */
            'publiccontrollers' => [
                'index',
                'error',
                'static',
                'content',
                'contact',
                'archive'
//                'forum'
            ],
            /**
             * <pre>
             * Resources are resources a user type may access.
             * The AclBuilder makes sure that users can access the resources defined here
             *
             * Access rights are inherited, so the least privileged should go on top.
             *
             * Format is Role => array (
             *      'controller' => array(actions) || string action
             * )
             * </pre>
             *
             */
            'resources' =>
            [
                AclBuilder::ROLE_NAME_GUEST => [
                    'account' => [
                        'register',
                        'login',
                        'resendConfirmation',
                        'forgotPassword',
                        'confirmUser',
                        'changePassword'
                    ],
                    'event' => ['show']

                ],
                AclBuilder::ROLE_NAME_USER => [
                    'account' => ['logout', 'manage'],
                    'event' => ['pay', 'register', 'seat', 'reserveSeat']
                ],
                AclBuilder::ROLE_NAME_ADMIN => [
                    'admin' => '*',
                    'index' => ['generate', 'addNews', 'deleteNews', 'editNews'],
                    'forum' => '*' //disable when forum goes live
                ]
            ]
        ],
        /**
         * Intranet ACL
         */
        'intranet' => [
            /**
             * Roles should be sorted in order of rights
             */
            'roles' => [
                AclBuilder::ROLE_ADMIN,
                AclBuilder::ROLE_MODERATOR,
                AclBuilder::ROLE_USER,
                AclBuilder::ROLE_GUEST
            ],
            /**
             * Public controllers are controllers available for ALL roles.
             * If only a certain role is allowed to access a action
             * within a public controller, then the action can be protected by adding it to the role.
             * Then only that role (and it's children) will inherit the rights to access it.
             */
            'publiccontrollers' => [
                'index',
                'error',
                'static',
                'content',
                'contact'
            ],
            /**
             * <pre>
             * Resources are resources a user type may access.
             * The AclBuilder makes sure that users can access the resources defined here
             *
             * Access rights are inherited, so the least privileged should go on top.
             *
             * Format is Role => array (
             *      'controller' => array(actions) || string action
             * )
             * </pre>
             *
             */
            'resources' =>
                [
                    AclBuilder::ROLE_NAME_GUEST => [
                        'account' => [
                            'login'
                        ]

                    ],
                    AclBuilder::ROLE_NAME_USER => [
                        'account' => ['logout', 'manage'],
                        'tournament' => ['index', 'signup', 'overview', 'view', 'cancel']
                    ],
                    AclBuilder::ROLE_NAME_ADMIN => [
                        'admin' => '*',
                        'index' => ['generate', 'addNews', 'deleteNews', 'editNews'],
                        'tournament' => ['addScore', 'removeUser', 'start', 'end', 'reset' , 'generateteams', 'editTeamName', 'updateRank']
                    ]
                ]
        ]
    ]
);

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
            'error'
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
                AclBuilder::ROLE_GUEST => [
                    'account' => [
                        'register',
                        'login',
                        'resendConfirmation',
                        'forgotPassword',
                        'confirmUser',
                        'changePassword'
                    ],
                    'event' => ['show', 'register', 'pay']
                ],
                AclBuilder::ROLE_USER => [
                    'account' => ['logout', 'manage']
                ],
                AclBuilder::ROLE_ADMIN => [
                    'admin' => 'index',
                    'index' => 'generate',
                ]
            ]
    ]
);

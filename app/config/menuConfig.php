<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 */
use Soul\Menu\Builder;

/**
 * Menu configuration
 *
 * Title => Controller/Action
 *
 * If submenu's have a link, structure it like this:
 *
 * 'Title' => [
 *   'link' => 'some/page',
 *   'some-name' => 'some/other/page'
 * ]
 */


// menu available for everybody
$menuConfig = [

        'Home' => BASE_URL . '/home',
        'Archief' => [
            'Soul-Soldiers: The Reunion 2013' => BASE_URL.'/event/the-reunion',
            'Soul-Soldiers 2014' => BASE_URL.'/event/soul-2014'
        ],
        // guest only menu items
        'guest' => [
            'Inloggen',
            'Registreren'
        ], 
        // Admin only menu items
        'admin' => [
            'Admin'
        ]

    ],
    /**
     * Intranet menu
     */
    'intranet' => [

        'menu'=> [
            '<span class="icon-home"></span> Home' => BASE_URL . '/home',
            '<span class="icon-user-1"></span> Account beheren' => BASE_URL . '/account/manage',
            '<span class="icon-award"></span> Tournooien' => [
                'Unreal Tournament 2004' => BASE_URL . '/tournaments/unreal-tournament',

            ],
            '<span class="icon-logout"></span> Uitloggen' => BASE_URL . '/logout',
        ],
        // guest only menu items
        'guest' => [

        ],
        // authenticated only menu items
        'authenticated' => [
            'Tournooien'
        ],
        // Admin only menu items
        'admin' => [
            'Tournooien beheren' => BASE_URL . '/tournaments/manage'
        ]
    ],

    'intranet-user' => [
        'menu' => [
            'Mijn account' => BASE_URL . '/account/manage',
            'Uitloggen' => BASE_URL.'/logout'
        ]
    ]

];
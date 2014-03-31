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
    'menu' => [

        'Home' => BASE_URL . '/home',
        'Archief' => [
            'Soul-Soldiers: The Reunion 2013' => BASE_URL.'/event/the-reunion'
        ],
        'Informatie' => [
            'Regelement' => BASE_URL.'/content/rules',
            'Lan-Party checklist' => BASE_URL.'/content/checklist',
            'Wat is een lan party?' => BASE_URL.'/content/lan-description'
        ],
        'Aankomend evenement' => [
            'link' => BASE_URL.'/event/current',
            'Competities' => BASE_URL.'/content/compos'
        ],
        'Contact' => BASE_URL.'/contact',
//        'Downloads' => BASE_URL.'/content/downloads',
        'Inloggen' => BASE_URL.'/login',
        'Registreren' => BASE_URL.'/register',
        'Uitloggen' => BASE_URL.'/logout',


    ],
    // guest only menu items
    'guest' => [
        'Inloggen',
        'Registreren'
    ],
    // authenticated only menu items
    'authenticated' => [
        'Uitloggen',
        'Downloads',
        'Competities'
    ],
    // Admin only menu items
    'admin' => [
    ]
];
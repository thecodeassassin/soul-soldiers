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
        'Soul-Soldiers: The Reunion 2013' => BASE_URL.'/event/the-reunion'
    ],
    'Informatie' => [
        'Regelement' => BASE_URL.'/rules',
        'Lan-Party checklist' => BASE_URL.'/checklist',
        'Wat is een lan party?' => BASE_URL.'/lan-description',
        'Competities' => BASE_URL.'/compos'
    ],
    'Aankomend evenement' => BASE_URL.'/event/current',
    'Inloggen' => BASE_URL.'/login',
    'Uitloggen' => BASE_URL.'/logout',
    'Registreren' => BASE_URL.'/register',
    'Contact' => BASE_URL.'/contact'
];

// guest only menu items
$guestOnly = [
    'Inloggen',
    'Registreren'
];

// authenticated only menu items
$authenticatedOnly = [
    'Uitloggen'
];

// Admin only menu items
$adminOnly = [

];

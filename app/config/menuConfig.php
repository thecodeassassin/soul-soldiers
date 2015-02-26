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

/**
 * Show the tournaments in the menu
 */

$tournamentNames = [];
// todo make dynamic menu inserts possible
if (ACTIVE_MODULE == 'intranet') {
    $cache = Phalcon\DI::getDefault()->get('cache');
    $url = Phalcon\DI::getDefault()->get('url');
    $tournamentNames = $cache->get('tournament_menu');

    if (!$tournamentNames) {
        $tournaments = \Soul\Model\Tournament::find();
        $tournamentNames = [];
        if ($tournaments) {
            foreach ($tournaments as $tournament) {
                $tournamentNames[$tournament->name] = $url->get('tournament/view/'.$tournament->systemName);
            }
        }

        // cache the menu for a day
        $cache->save('tournament_menu', $tournamentNames, 86400);

    }
}

// menu available for everybody
$menuConfig = [

     'website' => [

        'menu' => [

            'Home' => BASE_URL . '/home',
            'Archief' => [
                'Soul-Soldiers: The Reunion 2013' => BASE_URL.'/archive/the-reunion',
                'Soul-Soldiers 2014' => BASE_URL.'/archive/soul-2014',
                'Soul-Soldiers 2014: Autumn Edition' => BASE_URL.'/archive/soul2014-autumn'
            ],
            'Informatie' => [
                'Nieuws' => BASE_URL.'/news',
                'Algemene voorwaarden' => BASE_URL.'/content/rules',
                'LAN-Party checklist' => BASE_URL.'/content/checklist',
                'Wat is een LAN-party?' => BASE_URL.'/content/lan-description'
            ],
            'Aankomend evenement' => BASE_URL.'/event/current', //[
//                'link' => BASE_URL.'/event/current',
//                'Soul-Soldiers 2014: Autumn edition' => BASE_URL.'/event/current'
//                'Competities' => BASE_URL.'/content/compos'
//            ],
            'Forum' => BASE_URL.'/forum',
            'Mijn account' => BASE_URL.'/account/manage',
            'Admin' => BASE_URL.'/admin',
            'Contact' => BASE_URL.'/contact',

            'Inloggen' => BASE_URL.'/login',
            'Registreren' => BASE_URL.'/register',
            'Uitloggen' => BASE_URL.'/logout'
        ],
        // guest only menu items
        'guest' => [
            'Inloggen',
            'Registreren'
        ],
        // Admin only menu items
        'admin' => [
            'Admin',
            'Forum'
        ],
         // authenticated only menu items
         'authenticated' => [
             'Uitloggen',
             'Downloads',
             'Competities',
             'Mijn account'
         ]
     ],
    /**
     * Intranet menu
     */
    'intranet' => [

        'menu'=> [
            '<span class="icon-home"></span> Home' => BASE_URL . '/home',
            '<span class="icon-award"></span> Toernooien' => $tournamentNames,
            '<span class="icon-download"></span> Downloads' => BASE_URL . '/content/downloads',
            'Admin' => [
                 'Dashboard' => BASE_URL . '/admin/index',
                 'Toernooien' => BASE_URL . '/admin/tournaments'
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
            'Admin'
        ]
    ],

    'intranet-user' => [
        'menu' => [
//            'Mijn account' => BASE_URL . '/account/manage',
            'Uitloggen' => BASE_URL.'/logout'
        ]
    ]

];
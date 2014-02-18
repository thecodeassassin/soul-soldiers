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
$defaultMenu = [
    'Home' => '/',
    'Archief' => [
        'Soul-Soldiers: The Reunion 2013' => BASE_URL.'/archive/1'
    ],
    'Informatie' => [
        'link' => BASE_URL.'/information',
        'Regelement' => BASE_URL.'/rules',
        'Lan-Party checklist' => BASE_URL.'/checklist',
        'Wat is een lan party?' => BASE_URL.'/lan-description'
    ]
];

// guest menu
$guestMenu = [
    'Inloggen' => BASE_URL.'/login',
    'Registreren' => BASE_URL.'/register'
];

// authenticated only menu
$authenticatedMenu = [

];

// Admin only menu
$adminMenu = [

];

$di->set(
    'menu',
    function () use ($defaultMenu, $guestMenu, $authenticatedMenu, $adminMenu, $di) {

        $auth = $di->get('auth');
        $cache = $di->get('cache');

        if ($auth->isLoggedIn()) {
            $menuConfig = array_merge($defaultMenu, $authenticatedMenu);

            if ($auth->getUserType == \Soul\AclBuilder::ROLE_ADMIN) {
                $menuConfig = array_merge($menuConfig, $adminMenu);
            }
        } else {
            $menuConfig = array_merge($defaultMenu, $guestMenu);
        }


        $cacheKey = crc32(serialize($menuConfig));

        // disable translations cache in development
        $disableCache = (APPLICATION_ENV == \Phalcon\Error\Application::ENV_DEVELOPMENT ? true : false);

        if ($cache->exists($cacheKey) && !$disableCache) {
            return $cache->get($cacheKey);
        }

        // build the menu and save it in the cache
        $menu = Builder::build($menuConfig);
        $cache->save($cacheKey, $menu);


        return $menu;
    }
);

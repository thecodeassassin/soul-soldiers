<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

include __DIR__ . '/../menuConfig.php';

use Soul\Menu\Builder;

$di->set(
    'menu',
    function () use ($menuConfig, $authenticatedOnly, $adminOnly, $guestOnly, $di) {

        $auth = $di->get('auth');
        $cache = $di->get('cache');
        $loggedIn = $auth->isLoggedIn();

        // cache a different menu if the user is logged in
        if ($loggedIn) {
            $cacheKey = crc32(serialize($menuConfig).'_login');
        } else {
            $cacheKey = crc32(serialize($menuConfig));
        }

        if ($cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }


        foreach ($menuConfig as $name => $link) {
            if ($loggedIn) {
                if (in_array($name, $guestOnly)) {
                    unset ($menuConfig[$name]);
                }


                if (in_array($name, $adminOnly)) {
                    if ($auth->getUserType != \Soul\AclBuilder::ROLE_ADMIN) {
                       unset($menuConfig[$name]);
                    }
                }

            } else {
                if (in_array($name, $authenticatedOnly)) {
                    unset($menuConfig[$name]);
                }
            }
        }

        // build the menu and save it in the cache
        $menu = Builder::build($menuConfig);
        $cache->save($cacheKey, $menu);


        return $menu;
    }
);

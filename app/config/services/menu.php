<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

include __DIR__ . '/../menuConfig.php';

use Soul\Menu\Builder;


// set the menuconfig in the DI
$di->setShared('menuconfig', function() use ($menuConfig) {
    return $menuConfig;
});


$di->set('menu', function () use ($menuConfig, $di) {


        if (!class_exists('\Soul\Menu\\'.ucfirst(ACTIVE_MODULE))) {
            throw new \Exception(sprintf('No menu class exists for %s', ACTIVE_MODULE));
        }

        // build the menu and save it in the cache
        $menu = Builder::build($menuConfig[ACTIVE_MODULE], false, array(), '\Soul\Menu\\'.ucfirst(ACTIVE_MODULE));

        return $menu;
    }
);
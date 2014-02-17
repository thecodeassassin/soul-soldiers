<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 */

/**
 * Menu configuration
 *
 * Title => Controller/Action
 */
$menu = [
    'home' => 'index/index',

];

$di->set(
    'menu',
    function () use ($menu) {

        // disable translations cache in development
        $disableCache = (APPLICATION_ENV == \Phalcon\Error\Application::ENV_DEVELOPMENT ? true : false);
        return new \Soul\Menu($menu, $disableCache);
    }
);

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
        return new \Soul\Menu($menu);
    }
);

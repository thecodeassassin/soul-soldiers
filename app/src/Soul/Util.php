<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Phalcon Hosting
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Licensed under the Apache license V2
 * @namespace Soul
*/
namespace Soul;

/**
@package Hosting
 *
 * This is a utility class
*/
class Util {

    private function __construct(){}
    private function __clone(){}

    /**
     * <pre>
     * Simple 'pretty dump' method
     * Accepts multiple variables
     *
     *
     * Example:
     * </pre>
     *
     * <code>
     * Util::dump($var1, 'test', $var2));
     * </code>
     *
     */
    public static function dump() {
        $args = func_get_args();
        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }

        echo '</pre>';
    }

    /**
     * Returns the full current URL
     *
     * @return string
     */
    public static function getCurrentUrl()
    {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}



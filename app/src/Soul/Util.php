<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Phalcon Hosting
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Licensed under the Apache license V2
 * @namespace Soul
*/
namespace Soul;

use Phalcon\DI;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;

/**
@package Hosting
 *
 * This is a utility class
 *
 * Class Util
 *
 * @package Soul
 */
class Util {

    /**
     *
     */
    private function __construct(){}

    /**
     * Empty constructor
     */
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
    public static function dump()
    {
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
     * @param bool $removeParameters strip off any parameters
     *
     * @return string
     */
    public static function getCurrentUrl($removeParameters = false)
    {
        $uri = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ($removeParameters) {
             $uri = array_shift(parse_url($uri));
        }

        return "http://$uri";
    }

    /**
     * Generates a random password
     *
     * @param int $bytes
     *
     * @return mixed
     */
    public static function generateRandomPassword($bytes = 12)
    {
        if (!function_exists('openssl_random_pseudo_bytes')) {
            return static::generateRandomString($bytes);
        }

        return preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes((int) $bytes)));
    }

    /**
     * Generate a psuedo-random string
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < (int) $length; $i++) {
            $randomString = $characters[rand(0, strlen($characters))];
        }

        return $randomString;
    }

    /**
     * Get the user's ip address
     *
     * @return string
     */
    public static function getClientIp()
    {

        $ipAddress = '';

        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (array_key_exists('HTTP_X_FORWARDED', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (array_key_exists('HTTP_FORWARDED', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        return $ipAddress;

    }

    /**
     * Encrypt a string url-safe
     *
     * @param string $string
     * @return string
     */
    public static function encodeUrlSafe($string)
    {
        $crypt = DI::getDefault()->get('crypt');

        return strtr($crypt->encryptBase64($string), '+/=', '-_,');
    }

    /**
     * Encrypt a string url-safe
     *
     * @param string $string
     * @return string
     */
    public static function decodeUrlSafe($string)
    {
        $crypt = DI::getDefault()->get('crypt');

        return $crypt->decryptBase64(strtr($string, '-_,', '+/='));
    }

    /**
     * Strpos but with arrays
     *
     * @param string $haystack
     * @param array $needles
     * @param int $offset
     *
     * @return bool|mixed
     */
    public static function strposa($haystack, array $needles, $offset = 0)
    {
        $chr = array();
        foreach ($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $chr[$needle] = $res;
            }
        }

        if (empty($chr)) {
            return false;
        }
        return min($chr);
    }

    /**
     * Directory to array
     *
     * @param string $dir Directory
     * @return array
     */
    public static function dirToArray($dir)
    {

        $result = [];

        $cdir = scandir($dir);
        foreach ($cdir as $value) {
            if (!in_array($value, [".", ".."])) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = static::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }


    /**
     * Checks if a file is an image
     *
     * @param string $file path to the image
     * @return bool
     */
    public static function isImage($file)
    {
        if (!is_readable($file)) {
            return false;
        }

        $a = getimagesize($file);
        $image_type = $a[2];

        if (in_array($image_type, array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {
            return true;
        }

        return false;
    }
}

<?php
/**
 * @author Stephen Hoogendijk
 * @namespace Soul
*/
namespace Soul;
use Phalcon\Exception;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Translate\Adapter\NativeArray;

/**
@package Hosting
*/
class Translate extends Module
{

    /**
     * @var NativeArray
     */
    protected $translations;

    /**
     * @var Translate
     */
    static $instance;

    /**
     * @param string $language
     * @param array $manualTranslations
     * @param bool $disableCache
     * @return mixed
     */
    public function __construct($language = 'nl', array $manualTranslations = array(), $disableCache = false)
    {
        parent::__construct();
        /**
         * Initialize multiLingual support
         */

        $languagePath = $this->config->application->locales;

        $translationCacheKey = "translations_$language";
        if ($this->cache->exists($translationCacheKey) && !$disableCache) {
            $this->translations = $this->cache->get($translationCacheKey);
        } else {
            $messages = array();

            // manual translations can also be passed and will overwrite the language files
            if (count($manualTranslations) > 0) {
                $messages = $manualTranslations;
            } else {
                //Check if we have a translation file for that lang
                if (file_exists($languagePath. "$language.php")) {
                    include $languagePath. "$language.php";
                } else {
                    // fallback to some default
                    include $languagePath. "en.php";
                }
            }

            /*
             * Get a unique hash value for this (we use crc32 because of performance reasons)
             * The reason of doing caching like this is because we will add database translations in the future
             * for content management reasons.
             *
             * The maximum lifetime for translated messages is 1 hour
             */
            $translations = new NativeArray(
                [
                    "content" => $messages
                ]
            );

            if (!$disableCache) {
                // save the translations in the cache
                $this->cache->save($translationCacheKey, $translations, 3600);
            }

            //Return a translation object
            $this->translations = $translations;

        }


        // save the instance
        self::$instance = $this;

    }

    /**
     * @return NativeArray
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     *
     * Translation method primarily used on the frontend
     *
     * @param $key
     * @param array $params
     * @throws \Phalcon\Exception
     * @return mixed
     */
    public static function translate($key, array $params = array())
    {
        if (!self::$instance instanceof self) {
            throw new Exception('Translations need to be set first, initialize the object');
        }

        return vsprintf(self::$instance->translations[$key], $params);
    }

    /**
     * @param $key
     * @param array $params
     * @return mixed
     */
    public static function _($key, array $params = array())
    {
        return static::translate($key, $params);
    }
}



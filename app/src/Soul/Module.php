<?php
/**
 * @author Stephen Hoogendijk
*/
namespace Soul;

use Phalcon\Cache\Backend\Memcache;
use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\DiInterface;
use Phalcon\Logger\Adapter;
use Phalcon\Mvc\User\Plugin;
use Phalcon\DI as DI;
use Soul\Auth\AuthService;

/**
 *
 * Modules are plugins that are loaded after the initial services are loaded
*/
abstract class Module extends Plugin
{
    /**
     * @var Memcache
     */
    protected $cache;
    /**
     * @var mixed
     */
    protected $db;
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var DI
     */
    protected $di;

    /**
     * @var Adapter
     */
    protected $logger;


    /**
     * Constructor
     */
    public function __construct()
    {

        /**
         * Gets the necessary services for use in modules
         * @todo include security, session and auth services here
         */
        $this->di = DI::getDefault();


        $this->cache = ($this->di->has('cache') ? $this->di->get('cache') : null);
        $this->db = ($this->di->has('db') ? $this->di->get('db') : null);
        $this->config = ($this->di->has('config') ? $this->di->get('config') : null);

    }

    /**
     * @param DI|DiInterface $di
     */
    public function setDi(DiInterface $di)
    {
        $this->di = $di;
    }

    /**
     * @return \Phalcon\DI
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param \Phalcon\Cache\Backend\Libmemcached $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return \Phalcon\Cache\Backend\Libmemcached
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param \Phalcon\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return \Phalcon\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Mail
     */
    protected function getMail()
    {
        return $this->di->get('mail');
    }

    /**
     * @return Crypt
     */
    protected function getCrypt()
    {
        return $this->di->get('crypt');
    }

    /**
     * @return Adapter
     */
    protected function getLogger()
    {
        return $this->di->get('logger');
    }

    /**
     * @return AuthService
     */
    public static function getAuthService()
    {
        return  $auth = DI::getDefault()->get('auth');
    }
}



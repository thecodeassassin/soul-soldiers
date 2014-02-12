<?php
/**
 * @author Stephen Hoogendijk
*/
namespace Soul;
use Phalcon\Mvc\User\Plugin;
use phpDocumentor\Reflection\Exception;
use Phalcon\DI as DI;

/**
 *
 * Modules are plugins that are loaded after the initial services are loaded
*/
abstract class Module extends Plugin
{
    /**
     * @var mixed
     */
    public $cache;
    /**
     * @var mixed
     */
    public $db;
    /**
     * @var mixed
     */
    public $config;


    /**
     * Constructor
     */
    public  function __construct()
    {

        /**
         * Gets the necessary services for use in modules
         * @todo include security, session and auth services here
         */
        $di = DI::getDefault();


        $this->cache = ($di->has('cache') ? $di->get('cache') : null);
        $this->db = ($di->has('db') ? $di->get('db') : null);
        $this->config = ($di->has('config') ? $di->get('config') : null);

    }
}



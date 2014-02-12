<?php
/**
 * @author Stephen Hoogendijk
*/
namespace Soul;

use Soul\Module;
use Phalcon\Config;
use Phalcon\Db;
use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Exception;
use Phalcon\Cache\Backend\Apc;
use Phalcon\Paginator\Adapter\Model;

/**
@package Hosting
*/
abstract class ServiceBase extends Module
{


    /**
     * @var bool
     */
    private $constructed = false;

    /**
     * Main constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->constructed = true;
    }


    /**
     * Check if the constructor was called
     */
    public function __destruct()
    {
        if (!$this->constructed) {
            throw new Exception(sprintf('Invalid use of %s detected! Run parent::__construct().', __CLASS__));
        }
    }
}



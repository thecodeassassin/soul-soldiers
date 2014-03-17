<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *
 * @package Base
 */
namespace Soul\Model;

use Phalcon\Cache\BackendInterface;
use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Mvc\Model;
use Soul\Mail;

/**
 * Class Base
 *
 * Base model
 *
 * @package Soul\Model
 */
class Base extends Model
{

    /**
     * @return Mail
     */
    protected function getMail()
    {
        return $this->getDI()->get('mail');
    }

    /**
     * @return Crypt
     */
    protected function getCrypt()
    {
        return $this->getDI()->get('crypt');
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->getDI()->get('config');
    }

    /**
     * @return BackendInterface
     */
    protected function getCache()
    {
        return $this->getDI()->get('cache');
    }
}
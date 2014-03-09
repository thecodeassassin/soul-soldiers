<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *
 * @package Base
 */
namespace Soul\Model;

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
        return $this->di->get('mail');
    }

    /**
     * @return Crypt
     */
    protected function getCrypt()
    {
        return $this->di->get('crypt');
    }
}
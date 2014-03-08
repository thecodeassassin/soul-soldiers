<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *
 * @package Base
 */
namespace Soul\Model;
use Soul\Mail;

/**
 * Class Base
 *
 * Base model
 *
 * @package Soul\Model
 */
class Base extends  \Phalcon\Mvc\Model
{

    /**
     * @return Mail
     */
    protected function getMail()
    {
        return $this->di->get('mail');
    }
}
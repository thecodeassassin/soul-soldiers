<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *  @package Kernel
 */

namespace Soul\Kernel;

use Phalcon\DI;
use Phalcon\Error\Application as ErrorApplication;
use Soul\Kernel\Error\Handler;

/**
 * Soul Kernel (Main Application file)
 *
 * Class Kernel
 * @package Soul
 */
class Application extends ErrorApplication
{
    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {

        // set the DI
        $this->setDI($di);

        // call the parent constructor
        Handler::register();
    }

}
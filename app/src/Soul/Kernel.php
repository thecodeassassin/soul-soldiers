<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *  @package Kernel
 */

namespace Soul;

use Phalcon\DI;
use Phalcon\Error\Application;

/**
 * Soul Kernel (Main Application file)
 *
 * Class Kernel
 * @package Soul
 */
class Kernel extends Application
{
    public function __construct(DI $di)
    {
        // set the DI
        $this->setDI($di);

        // call the parent constructor
        parent::__construct();
    }

}
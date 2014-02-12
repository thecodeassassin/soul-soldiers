<?php

/**
 * Class ErrorController
 */
class ErrorController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function fatalAction()
    {
        die('FATAL ERROR');
    }

}


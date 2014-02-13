<?php
namespace Soul\Controller;

/**
 * Class ErrorController
 */
class ErrorController extends BaseController
{

    public function indexAction()
    {

    }

    public function fatalAction()
    {
        die('FATAL ERROR');
    }

}


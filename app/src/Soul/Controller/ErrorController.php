<?php
namespace Soul\Controller;

/**
 * Class ErrorController
 */
class ErrorController extends BaseController
{

    /**
     * Handle general errors
     */
    public function indexAction()
    {
        $error = $this->dispatcher->getParam('error');

        switch ($error->code()) {
            case 404:
                $code = 404;
                break;
            default:
                $code = 500;
        }

        $this->getDi()->getShared('response')->resetHeaders()->setStatusCode($code, null);
        $this->view->setVar('error', $error);
    }

    public function fatalAction()
    {
        die('FATAL ERROR');
    }

}


<?php
namespace Soul\Controller\Website;

use Phalcon\Error\Application;

/**
 * Class ErrorController
 */
class ErrorController extends \Soul\Controller\Base
{



    /**
     * Handle general errors
     */
    public function indexAction($httpStatusCode = null)
    {
        $httpStatusCode = (!is_null($httpStatusCode) ? $httpStatusCode : 500);

        $dispatchError = null;
        $error = null;

        if ($dispatchError = $this->dispatcher->getParam('error')) {
            $code = $dispatchError->code();

            // if we are on development, it's safe to show errors
            if (APPLICATION_ENV == Application::ENV_DEVELOPMENT) {
                $error = [
                    'code' => $code,
                    'message' => $dispatchError->message()
                ];
            } else {
                $error = compact('code');
            }
        }

        if ($httpStatusCode instanceof \Phalcon\Error\Error) {
            $httpStatusCode = 500;
        }

        $this->getDi()->getShared('response')->resetHeaders()->setStatusCode($httpStatusCode, null);
        $this->view->setVar('code', $httpStatusCode);
        $this->view->setVar('error', $error);

    }

    public function fatalAction()
    {
        $this->dispatcher->forward(
            [
                'controller' => 'error',
                'action'    => 'index',
                'params'    => [500]
            ]
        );
    }

    public function notFoundAction()
    {
        $this->dispatcher->forward(
            [
                'controller' => 'error',
                'action'    => 'index',
                'params'    => [404]
            ]
        );
    }


    public function notAuthenticatedAction()
    {
        $this->dispatcher->forward(
            [
                'controller' => 'error',
                'action'    => 'index',
                'params'    => [403]
            ]
        );
    }



}


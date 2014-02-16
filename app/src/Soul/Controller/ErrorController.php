<?php
namespace Soul\Controller;
use Phalcon\Error\Application;

/**
 * Class ErrorController
 */
class ErrorController extends BaseController
{



    /**
     * Handle general errors
     */
    public function indexAction($errorCode)
    {
        $code = (!is_null($errorCode) ? $errorCode : 404);
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


        switch ($code) {
            case 404:
                $code = 404;
                break;
            default:
                $code = 500;
        }

        $this->getDi()->getShared('response')->resetHeaders()->setStatusCode($code, null);
        $this->view->setVar('code', $code);
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



}


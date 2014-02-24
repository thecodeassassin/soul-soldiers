<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ErrorHandler 
 */  

namespace Soul\Kernel\Error;

use Phalcon\DI;
use Phalcon\Error\Error;
use Phalcon\Error\Handler as BaseHandler;
use Soul\Kernel\Application;

class Handler extends BaseHandler
{
    /**
     * Registers itself as error and exception handler.
     *
     * @return void
     */
    public static function register()
    {
        switch (APPLICATION_ENV) {
            case Application::ENV_PRODUCTION:
            case Application::ENV_STAGING:
            default:
                ini_set('display_errors', 0);
                error_reporting(0);
                break;
            case Application::ENV_TEST:
            case Application::ENV_DEVELOPMENT:
                ini_set('display_errors', 1);
                error_reporting(-1);
                break;
        }

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if (!($errno & error_reporting())) {
                return;
            }

            $options = array(
                'type'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'isError' => true,
            );

            static::handle(new Error($options));
        });

        set_exception_handler(function (\Exception $e) {
            $options = array(
                'type'        => $e->getCode(),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'isException' => true,
                'exception'   => $e,
            );

            static::handle(new Error($options));
        });

        register_shutdown_function(function () {
            if (!is_null($options = error_get_last())) {
                static::handle(new Error($options));
            }
        });
    }

    /**
     * Logs the error and dispatches an error controller.
     *
     * @param \Phalcon\Error\Error $error Error to be handled
     * @return mixed
     */
    public static function handle(Error $error)
    {

        $di = DI::getDefault();
        $config = $di->getShared('config');
        $type = static::getErrorType($error->type());
        $message = "$type: {$error->message()} in {$error->file()} on line {$error->line()}";

        $config->error->logger->log($message);

        switch ($error->type()) {
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
                break;
            default:
                $dispatcher = $di->getShared('dispatcher');
                $view = $di->getShared('view');
                $response = $di->getShared('response');

                $dispatcher->setControllerName($config->error->controller);
                $dispatcher->setActionName($config->error->action);
                $dispatcher->setParams(array('error' => $error));

                $view->start();
                $dispatcher->dispatch();
                $view->render($config->error->controller, $config->error->action, $dispatcher->getParams());
                $view->finish();

                return $response->setContent($view->getContent())->send();
            break;
        }
    }
} 
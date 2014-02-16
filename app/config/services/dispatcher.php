<?php

use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

$di->set('dispatcher', function() use ($config){

    $dispatcher = new \Phalcon\Mvc\Dispatcher();

    $eventsManager = new \Phalcon\Events\Manager();

    // when working on the development environment, we need the debugger to show us what is going bad
    if (APPLICATION_ENV != "development") {




    }

    /**
     * attach a listener to redirect to the login page if not logged
     */
    $eventsManager->attach("dispatch:beforeExecuteRoute", function($event, $dispatcher, $exception) {
        /* @var $dispatcher \Phalcon\Dispatcher */

        $authService = $dispatcher->getDi()->get("auth");

        // if user is not logged and not in the AuthController, then we redirect him to the login screen
        if (!$authService->isLoggedIn() && $dispatcher->getControllerName() != "auth") {
            return $dispatcher->getDi()->get("response")->redirect('login');
        }

        return true;

    });


    //Bind the eventsManager to the view component
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

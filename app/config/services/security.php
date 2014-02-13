 <?php
//
 /**
  * Initialize the security plugin
  */
$di->set('dispatcher', function() use ($di) {

    //Obtain the standard eventsManager from the DI
    $eventsManager = $di->getShared('eventsManager');

    // Instantiate the Security plugin
     $security = new \Soul\Security($di);

    //Listen for events produced in the dispatcher using the Security plugin
     $eventsManager->attach('dispatch', $security);

     $dispatcher = new Phalcon\Mvc\Dispatcher();
     $dispatcher->setDefaultNamespace('Soul\Controller');
    /*
        * Attach a listener for 404 and other errors
        */
    $eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {

        //Handle 404 exceptions
        if ($exception instanceof DispatchException) {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'notFound'
            ));

            return false;
        }

        // Not a 404, then we trigger an error (that aims go in the error log)
        trigger_error("An exception has been detected from the dispatcher event :".$exception->getMessage(),E_USER_WARNING);

        //Handle other exceptions
        $dispatcher->forward(array(
            'controller' => 'error',
            'action' => 'fatal'
        ));

        return false;
    });

     //Bind the EventsManager to the Dispatcher
     $dispatcher->setEventsManager($eventsManager);

     return $dispatcher;
    });

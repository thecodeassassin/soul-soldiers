 <?php
//
 /**
  * Initialize the security plugin
  */
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

$di->set('dispatcher', function() use ($di) {

    //Obtain the standard eventsManager from the DI
    $eventsManager = $di->getShared('eventsManager');
    $securityClass = '\Soul\Security\\'.ucfirst(ACTIVE_MODULE);

    if (!class_exists($securityClass)) {
        throw new \Exception(sprintf('No security class exists for %s', ACTIVE_MODULE));
    }

    // Instantiate the Security plugin
    $security = new $securityClass($di);

    //Listen for events produced in the dispatcher using the Security plugin
    $eventsManager->attach('dispatch', $security);

    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace('Soul\Controller\\'.ucfirst(ACTIVE_MODULE));

    //Bind the EventsManager to the Dispatcher
    $dispatcher->setEventsManager($eventsManager);

     return $dispatcher;
});

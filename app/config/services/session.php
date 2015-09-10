<?php
/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function() {

    if (APPLICATION_ENV == 'development') {
        $session = new \Phalcon\Session\Adapter\Files(
            [
                'uniqueId' => 'soul-soldiers'
            ]
        );
    } else {
        $session = new \Phalcon\Session\Adapter\Libmemcached(
            [
                'host' => 'localhost',
                'lifetime' => 43200 // store for 12 hours
            ]
        );
    }

    $session->start();

    return $session;
});
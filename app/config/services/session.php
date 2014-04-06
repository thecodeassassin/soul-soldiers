<?php
/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function() {

    $session = new \Phalcon\Session\Adapter\Memcache(
        [
            'host' => 'localhost',
            'lifetime' => 43200 // store for 12 hours
        ]
    );

    $session->start();

    return $session;
});
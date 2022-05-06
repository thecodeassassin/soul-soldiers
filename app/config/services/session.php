<?php
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Adapter\Database;
/**
 * Start the session the first time some component request the session service
 */
//$di->setShared('session', function() {
//
//    $session = new \Phalcon\Session\Adapter\Memcache(
//        [
//            'host' => 'localhost',
//            'lifetime' => 43200 // store for 12 hours
//        ]
//    );
//
//    $session->start();
//
//    return $session;
//});
//

$di->setShared(
    'session',
    function () use ($config) {
        $connection = new Mysql(
            [
                "host" => $config->database->host,
                "port" => (isset($config->database->port) ? $config->database->port : 3306),
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ]
        );

        $session = new Database(
            [
                'db'    => $connection,
                'table' => 'session_data',
            ]
        );

        $session->start();

        return $session;
    }
);
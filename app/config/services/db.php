<?php
/**
 * Database connection is created based in the parameters defined in the configuration file
 */

use Phalcon\Db\Adapter\Pdo\Mysql;

if ($config->database->host) {
    $di->set('db',function() use ($config) {
        return new Mysql(
            array(
            "host" => $config->database->host,
            "port" => (isset($config->database->port) ? $config->database->port : 3306),
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname
            )
        );
    });
}
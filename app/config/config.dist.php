<?php
return new \Phalcon\Config(
    [
        'database' => [
            'adapter'     => 'Mysql',
            'host'        => '',
            'username'    => '',
            'password'    => '',
            'dbname'      => '',
        ],
        'application' => [
            'libraryDir'     => APPLICATION_PATH . '/src/',
            'pluginsDir'     => APPLICATION_PATH . '/plugins/',
            'cacheDir'       => APPLICATION_PATH . '/cache/',
            'locales'        => APPLICATION_PATH .'/locales/',
            'baseUri'        => '/',
            'baseTitle'      => 'Soul-Soldiers - Lan parties'
        ],
        'error' => [
            'logger' => new \Phalcon\Logger\Adapter\File(APPLICATION_PATH . '/log/' . APPLICATION_ENV . '.log'),
            'controller' => 'error',
            'action' => 'index',
        ]
    ]
);
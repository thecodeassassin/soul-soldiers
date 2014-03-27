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
            'cacheDir'       => APPLICATION_PATH . '/cache',
            'locales'        => APPLICATION_PATH .'/locales/',
            'mediaDir'       => APPLICATION_PATH .'/../public/media',
            'mediaUrl'       => '/media',
            'baseUri'        => '/',
            'baseTitle'      => 'Soul-Soldiers - Lan parties'
        ],
        'analytics' =>[
            'code' => '',
        ],
        'paymentServices' => [
            'targetPay' => [
                'testMode' => true,
                'layoutCode' => '',
                'returnUrl' => BASE_URL . '/event/current',
                'reportUrl' => ''
            ]
        ],
        'error' => [
            'logger' => new \Phalcon\Logger\Adapter\File(APPLICATION_PATH . '/log/' . APPLICATION_ENV . '.log'),
            'controller' => 'error',
            'action' => 'index',
        ],
        'mail' => [
            'fromName' => 'Soul-Soldiers',
            'fromEmail' => 'info@soul-soldiers.nl',
            'smtp' => [
                'server' => 'smtp.gmail.com',
                'port' => 587,
                'security' => 'tls',
                'username' => '',
                'password' => ''
            ],
            'provider' => 'Swift',
            'adminAddress' => 'info@soul-soldiers.nl'
        ],
        'crypt' => [
            'key' => ''
        ]
    ]
);
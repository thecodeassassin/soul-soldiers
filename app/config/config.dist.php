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
            'libraryDir'     => __DIR__ . '/../src/',
            'pluginsDir'     => __DIR__ . '/../plugins/',
            'modelsDir'     => __DIR__ . '/../src/Soul/Model',
            'cacheDir'       => __DIR__ . '/../cache/',
            'locales'        => __DIR__ . '/../locales/',
            'mediaDir'       => __DIR__ .'/../../public/media',
            'mediaUrl'       => '/media',
            'baseUri'        => '/',
            'intranet' => [
                'baseTitle' => 'Soul-Soldiers Intranet'
            ],
            'website' => [
                'baseTitle' => 'Soul-Soldiers - Lan parties',
            ]
        ],
        /**
         * Controls who can access the staging environment (list of ips)
         */
        'stagingAccess' => [

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
            'adminAddress' => 'info@soul-soldiers.nl',
            'adminName' => 'Soul-Soldiers Bestuur'
        ],
        'crypt' => [
            'key' => ''
        ],
        'challonge' => [
            'apiKey' => ''
        ]
    ]
);
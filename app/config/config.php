<?php

return new \Phalcon\Config(
    [
        'database' => [
            'adapter'     => 'Mysql',
            'host'        => getenv("DATABASE_HOST"),
            'username'    => getenv("DATABASE_USERNAME"),
            'password'    => getenv("DATABASE_PASSWORD"),
            'dbname'      => getenv("DATABASE_NAME"),
        ],
        'application' => [
            'libraryDir'     => APPLICATION_PATH . '/src/',
            'pluginsDir'     => APPLICATION_PATH . '/plugins/',
            'cacheDir'       => APPLICATION_PATH . '/cache/',
            'locales'        => APPLICATION_PATH .'/locales/',
            'mediaDir'       => APPLICATION_PATH .'/../public/media',
            'mediaUrl'       => '/media',
            'baseUri'        => '/',           
            'intranet' => [  
               'baseTitle' => 'Soul-Soldiers Intranet'
            ],  
            'website' => [
               'baseTitle' => 'Soul-Soldiers - Lan parties',
            ]   

        ],
        'analytics' =>[
            'code' => getenv("GA_CODE")
        ],
        'paymentServices' => [
            'targetPay' => [
                'testMode' => getenv("TARGETPAY_TEST_MODE") == 'true',
                'layoutCode' => getenv("TARGETPAY_LAYOUT_CODE"),
                'returnUrl' => BASE_URL . '/event/current',
                'reportUrl' => ''
            ]
        ],
        'error' => [
            'logger' => new Phalcon\Logger\Adapter\Syslog(null),
            'controller' => 'error',
            'action' => 'index',
        ],
        'mail' => [
            'fromName' => 'Soul-Soldiers',
            'fromEmail' => 'info@soul-soldiers.nl',
            'smtp' => [
                'server' => getenv("SMTP_SERVER"),
                'port' => 587,
                'security' => 'tls',
                'username' => getenv("SMTP_USERNAME"),
                'password' => getenv("SMTP_PASSWORD"),
            ],
            'provider' => 'Swift',
            'adminAddress' => 'stephen@soul-soldiers.nl',
            'adminName' => 'Soul-Soldiers Bestuur'

        ],
        'crypt' => [
            'key' => getenv("CRYPT_KEY"),
        ],
        'challonge' => [
            'apiKey' => ''
        ],
        'captcha' => [
            'siteKey' => getenv("CAPTCHA_SITEKEY"),
            'secretKey' => getenv("CAPTCHA_SECRETKEY")
        ],
    ]
);

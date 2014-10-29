<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * Read auto-loader
 */
include __DIR__ . "/services/loader.php";


// load the caching service
include __DIR__ . "/services/caches.php";


// load the session service
include __DIR__ . "/services/session.php";

// load the translation service
include __DIR__ . "/services/translate.php";

// load the view services
include __DIR__ . "/services/view.php";

// load the url service
include __DIR__ . "/services/url.php";

// load the db service
include __DIR__ . "/services/db.php";

// load the flash service
include __DIR__ . "/services/flash.php";

// load the ACL
include __DIR__ . "/services/acl.php";

// load the dispatcher
include __DIR__ . "/services/dispatcher.php";

// load crypt
include __DIR__ . "/services/crypt.php";

// load the routes
include __DIR__ . "/services/router.php";

// load the menu config
include __DIR__ . "/services/menu.php";

// load the mailer
include __DIR__ . "/services/mail.php";

// load the assets manager
include __DIR__ . "/services/assets.php";

// load auth services
include __DIR__ . "/services/auth.php";

if (ACTIVE_MODULE == 'intranet') {

    // enable challonge for the intranet site
    $di->setShared('challonge' , function() use ($config) {
        return new \Soul\Tournaments\Challonge($config->challonge->apiKey, $config->challonge->subdomain);
    });

    $di->setShared('binarybeast', function() use ($config) {
        $bbConfig = new BBConfiguration();
        $bbConfig->api_key = $config->binarybeast->key;
        $bbConfig->cache_db_database = $config->database->dbname;
        $bbConfig->cache_db_password = $config->database->password;
        $bbConfig->cache_db_username = $config->database->username;
        $bbConfig->cache_db_server = $config->database->host;
        $bbConfig->cache_db_table = 'tblBBApiCache';
        $bbConfig->cache_default_ttl = 5;

        return new BinaryBeast($bbConfig);

    });

}

$di->setShared('logger', function() use ($config) {
    return $config->error->logger;
});
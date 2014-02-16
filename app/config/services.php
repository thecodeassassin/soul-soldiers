<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

// read all the services


// load the caching service
include __DIR__."/services/caches.php";


// load the session service
include __DIR__."/services/session.php";

// load the translation service
include __DIR__."/services/translate.php";

// load the view services
include __DIR__."/services/view.php";

// load the url service
include __DIR__."/services/url.php";

// load the db service
include __DIR__."/services/db.php";

// load the flash service
include __DIR__."/services/flash.php";

// load the ACL
include __DIR__."/services/acl.php";

// load the security service
include __DIR__."/services/security.php";

// load the routes
include __DIR__ . "/services/router.php";

// load the menu config
include __DIR__ . "/menu.php";

// load auth services

// load the config into the DI
$di->set('config', $config);
include __DIR__ . "/services/auth.php";
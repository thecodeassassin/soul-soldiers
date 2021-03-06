<?php
use Phalcon\Mvc\Url as UrlResolver;

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri(BASE_URL.'/');

    return $url;
}, true);

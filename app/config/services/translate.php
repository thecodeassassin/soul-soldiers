<?php

$session = $di->get('session');

$language = 'nl';
if ($session->get('language')) {
    $language = $session->get('language');
}

$di->set('translate', function() use ($config, $language) {

    // disable translations cache in development
    $disableCache = (APPLICATION_ENV == \Phalcon\Error\Application::ENV_DEVELOPMENT ? true : false);

    return new \Soul\Translate($language, array(), $disableCache);
});
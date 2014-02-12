<?php

$session = $di->get('session');

$language = 'nl';
if ($session->get('language')) {
    $language = $session->get('language');
}

$di->set('translate', function() use ($config, $language) {
    return new \Soul\Translate($language);
});
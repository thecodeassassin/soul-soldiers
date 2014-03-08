<?php
$di->set('mail', function() use ($config) {

    $providerSetting = $config->mail->provider;
    $provider = null;

    if ($providerSetting == 'SES') {
        $provider = new \Soul\Mail\Provider\SES();
    }

    return new \Soul\Mail($provider);
});
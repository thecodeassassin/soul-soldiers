<?php
$di->set('mail', function() use ($config) {

    $providerSetting = $config->mail->provider;
    $provider = null;

    if ($providerSetting == 'Swift') {
        $provider = new \Soul\Mail\Provider\Swift();
    }

    return new \Soul\Mail($provider);
});
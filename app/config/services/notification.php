<?php

$di->setShared('notification', function() use ($config, $language) {
    return new \Soul\NotificationService();
});

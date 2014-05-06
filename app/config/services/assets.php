<?php

$assetsConfig = include __DIR__ . '/../' . 'assetsConfig.php';

$di->setShared("assets", function() use ($di, $assetsConfig){

    if (!array_key_exists(ACTIVE_MODULE, $assetsConfig)) {
        throw new \Exception(sprintf('%s has no assets!', $assetsConfig));
    }

    $assetManager = new \Soul\Assets\Manager($di, $assetsConfig[ACTIVE_MODULE]);

    return $assetManager;

});

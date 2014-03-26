<?php

$assetsConfig = include __DIR__ . '/../' . 'assetsConfig.php';

$di->setShared("assets", function() use ($di, $assetsConfig){

    $assetManager = new \Soul\Assets\Manager($di, $assetsConfig);

    return $assetManager;

});

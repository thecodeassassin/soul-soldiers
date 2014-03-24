<?php

$assetsConfig = include __DIR__ . '/../' . 'assetsConfig.php';

$di->setShared("assets", function() use ($di, $assetsConfig){

    $assetManager = new \Phalcon\Assets\Manager();
    $managers = [];

    if (!empty($assetsConfig)) {
        foreach ($assetsConfig as $collection => $types) {

            $collectionManager = $assetManager->collection($collection);
            $managers[$collection] = $collectionManager;

            foreach ($types as $type => $assets) {

                foreach ($assets as $asset) {
                    $internal = (strpos($asset, '//') === false);


                    if ($type == 'css') {
                        $collectionManager->addCss($asset, false, ($internal ? true : false));
                    } else {
                        $collectionManager->addJs($asset, $internal, ($internal ? true : false));
                    }
                }
            }

        }
    }

    foreach ($managers as $collection => $manager) {



        $manager->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Cssmin())
            ->setTargetPath(APPLICATION_PATH . "/cache/{$collection}_css")
            ->setTargetUri("static/$collection")

            ->addFilter(new \Phalcon\Assets\Filters\Jsmin())
            ->setTargetPath(APPLICATION_PATH . "/cache/{$collection}_js")
            ->setTargetUri("static/$collection");

    }

    return $assetManager;

});

<?php
use Phalcon\DI;
use Phalcon\Mvc\View,
    Soul\Translate,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;


/**
 * Setting up the view component
 */
$di->set('view', function() use ($config, $di) {

        $view = new View();

        $view->setViewsDir($config->application->libraryDir.'Soul/View/');

        $view->registerEngines([
                '.volt' => function ($view, $di) use ($config) {

                        $volt = new VoltEngine($view, $di);

                        $volt->setOptions([
                                'compiledPath' => $config->application->cacheDir,
                                'compiledSeparator' => '_',
                                'compileAlways' => true
                            ]);

                        $compiler = $volt->getCompiler();

                        $compiler->addFunction(
                            't',
                            function ($key) {
                                return Translate::translate($key);
                            }
                        );

                        $compiler->addFunction(
                            'embed_image',
                            function ($args, $params) use ($compiler, $di) {

                                $image =  str_replace("'", "", $compiler->expression($params[0]['expr']));

                                $fullPath = sprintf('%s/../public/%s', APPLICATION_PATH, $image);

                                if (!is_readable($fullPath)) {
                                    return '""';
                                }

                                $imageBase64 = base64_encode(file_get_contents($fullPath));
                                $alt = '';

                                if (isset($params[1])) {
                                    $alt = $compiler->expression($params[1]['expr']);
                                }

                                return '"<img src=\'data:image/png;base64,'.$imageBase64.'\' alt='.$alt.'>"';
                            }
                        );

                        return $volt;
                    },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php' // Generate Template files uses PHP itself as the template engine
            ]);

        return $view;
    }, true);

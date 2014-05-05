<?php
use Phalcon\DI;
use Phalcon\Mvc\View,
    Soul\Translate,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;


/**
 * Setting up the view component
 */
$di->set('view', function() use ($config, $di, $viewDir) {

        $view = new View();

        $view->setViewsDir($viewDir);

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
                            'email_embed',
                            function ($args, $params) use ($compiler, $di) {

                                $image = str_replace("'", "", $compiler->expression($params[0]['expr']));

                                $fullPath = sprintf('%s/../public/%s', APPLICATION_PATH, $image);

                                if (!is_readable($fullPath)) {
                                    return '""';
                                }

                                return '"[embed]'.$fullPath.'[/embed]"';
                            }
                        );

                        return $volt;
                    },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php' // Generate Template files uses PHP itself as the template engine
            ]);

        return $view;
    }, true);

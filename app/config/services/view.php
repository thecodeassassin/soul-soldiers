<?php
use Phalcon\DI;
use Phalcon\Mvc\View,
    Soul\Translate,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;

/**
 * Setting up the view component
 */
$di->setShared('view', function() use ($config, $di) {

        $view = new View();

        if (!is_readable($config->application->libraryDir.'Soul/View/'.ucfirst(ACTIVE_MODULE))) {
            throw new \Exception('View directory not readable');
        }

        $view->setViewsDir($config->application->libraryDir.'Soul/View/'.ucfirst(ACTIVE_MODULE));
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
});

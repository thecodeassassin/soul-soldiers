<?php
namespace Soul\Module;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface,
    Phalcon\DI,
    Soul\Translate,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;


/**
 * Class Intranet
 *
 * @package Soul\Module
 */
class Intranet implements ModuleDefinitionInterface
{

    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders($di)
    {

    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {

        $config = $di->get('config');

        /*
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

                                return $volt;
                            },
                        '.phtml' => 'Phalcon\Mvc\View\Engine\Php' // Generate Template files uses PHP itself as the template engine
                    ]);

                return $view;
            });


    }

}
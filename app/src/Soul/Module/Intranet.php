<?php
namespace Soul\Module;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface;

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
    public function registerAutoloaders()
    {

        $loader = new Loader();

        $loader->registerNamespaces(
            array(
                'Multiple\Backend\Controllers' => '../apps/backend/controllers/',
                'Multiple\Backend\Models'      => '../apps/backend/models/',
            )
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {
        die('anal');
//        $config = $di->get('config');
//        $viewDir = $config->application->libraryDir.'Soul/View/';
//
//        include __DIR__. '/../../config/intranetServices.php';



    }

}
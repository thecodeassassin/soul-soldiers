<?php
use Phalcon\DI;
use Phalcon\Mvc\View,
    Soul\Translate,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;

/**
 * Setting up the view component
 */
$di->setShared(
    'view',
    function () use ($config, $di) {

        $view = new View();

        if (!is_readable($config->application->libraryDir . 'Soul/View/' . ucfirst(ACTIVE_MODULE))) {
            throw new \Exception('View directory not readable');
        }

        $view->setViewsDir($config->application->libraryDir . 'Soul/View/' . ucfirst(ACTIVE_MODULE));
        $view->registerEngines(
            [
                '.volt' => function ($view, $di) use ($config) {

                    $volt = new VoltEngine($view, $di);

                    $volt->setOptions(
                        [
                            'compiledPath' => $config->application->cacheDir,
                            'compiledSeparator' => '_',
                            'compileAlways' => true
                        ]
                    );

                    $compiler = $volt->getCompiler();

                    $compiler->addFunction(
                        't',
                        function ($key) {
                            return Translate::translate($key);
                        }
                    );

                    $compiler->addFunction(
                        'rand',
                        function ($resolvedArgs) {
                            return 'mt_rand(' . $resolvedArgs . ')';
                        }
                    );

                    $compiler->addFilter(
                        'date',
                        function ($resolvedArgs, $exprArgs) use ($compiler) {
                            $firstArgument = $compiler->expression($exprArgs[0]['expr']);
                            $secondArgument = $compiler->expression($exprArgs[1]['expr']);

                            return 'date(' . $secondArgument . ', strtotime(' . $firstArgument . '))';
                        }
                    );

                    $compiler->addFilter(
                        'base64',
                        function ($resolvedArgs, $exprArgs) use ($compiler) {
                            return 'base64_encode(' . $resolvedArgs . ')';
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

                            return '"[embed]' . $fullPath . '[/embed]"';
                        }
                    );

                    $compiler->addFunction(
                        'gravatar_url',
                        function ($args, $params) use ($compiler, $di) {

                            $email = str_replace("'", "", $compiler->expression($params[0]['expr']));


                            $size = 80;

                            if (isset($params[1])) {
                                $size = $compiler->expression($params[1]['expr']);
                            }

                            $url = "http://www.gravatar.com/avatar/%s?s=$size&d=mm&r=r";

                            return "sprintf('$url', md5(strtolower(trim($email))))";
                        }
                    );

                    $compiler->addFilter(
                        'replace',
                        function ($resolvedArgs, $exprArgs) use ($compiler) {

                            $str = $exprArgs[0]['expr']['value'];
                            $find = $exprArgs[1]['expr']['value'];
                            $replace = $exprArgs[2]['expr']['value'];

                            return "str_replace('$find', '$replace', $str)";
                        }
                    );

                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
                // Generate Template files uses PHP itself as the template engine
            ]
        );

        return $view;
    }
);

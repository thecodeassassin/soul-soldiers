<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AbstractProvider 
 */  

namespace Soul\Mail\Provider;


 
use Phalcon\Mvc\View;
use Soul\Module;

abstract class AbstractProvider extends Module implements ProviderInterface
{
    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array $params
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge([
            'publicUrl' => BASE_URL
        ], $params);

        return $this->view->getRender('email', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

    }

    abstract public function send(array $to, $subject, $templateName, array $templateParams);
} 
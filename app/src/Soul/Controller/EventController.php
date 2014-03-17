<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package EventController 
 */  

namespace Soul\Controller;

use Phalcon\Mvc\View;
use Soul\Model\Event;

/**
 * Class EventController
 *
 * @package Soul\Controller
 */
class EventController extends Base
{
    /**
     * Show an event
     *
     * @param string $systemName Event system name
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function showAction($systemName)
    {
        $pictures = array();
        $registered = false;
        $payed = false;

        if ($systemName == 'current') {
            $event = Event::getCurrent();
        } else {

            if (!$event = Event::findBySystemName($systemName)) {
                $this->flashMessage('Dit evenement bestaat niet', 'error', true);
                $this->setLastPage();

                return $this->redirectToLastPage();
            }

        }

        $user = $this->authService->getAuthData();
        if ($event && $user) {
            $registered = $event->hasEntry($user->getUserId());

            // check if the user has payed for the event
            if ($registered && array_key_exists('payed', $registered) && $registered['payed'] == true) {
                $payed = true;
            }
        }

        $this->view->registered = $registered;
        $this->view->payed = $payed;
        $this->view->event = $event;
        $this->view->media = $event->getMedia();
    }

    /**
     * @param $systemName
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function registerAction($systemName)
    {

        $user = $this->authService->getAuthData();
        $event = Event::findBySystemName($systemName);

        if ($user && $event) {
            if ($event->registerByUserId($user->getUserId())) {
                $this->flashMessage(sprintf('Je bent nu ingeschreven voor %s!', $event->name), 'success', true);
            }           
        }

        return $this->redirectToLastPage();
    }

} 
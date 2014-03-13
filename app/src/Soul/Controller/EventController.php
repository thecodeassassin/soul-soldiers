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
        if ($systemName == 'current') {
            $event = Event::getCurrent();
        } else {

            if (!$event = Event::findFirstBySystemName($systemName)) {
                $this->flashMessage('Dit evenement bestaat niet', 'error', true);
                $this->setLastPage();

                return $this->redirectToLastPage();
            }

        }

        $eventView = $this->view->getRender('event', $event->systemName, compact('event'), function ($view) {
             $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        $this->view->eventView = $eventView;
        $this->view->event = $event;
    }
} 
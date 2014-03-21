<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package EventController
 */

namespace Soul\Controller;

use Phalcon\Mvc\View;
use Soul\Model\Event;
use Soul\Payment\Service\TargetPay;

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
            if ($registered && $event->hasPayed($user->getUserId())) {
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

    /**
     * Pay for a given event
     *
     * @param string $systemName
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function payAction($systemName)
    {
        $paymentService = new TargetPay();

        $event = Event::findBySystemName($systemName);
        $user = $this->authService->getAuthData();
        if ($event && $user) {

            // check if a user has payed already
            if ($event->hasPayed($user->getUserId()) || !$event->hasEntry($user->getUserId())) {
                return $this->redirectToLastPage();
            }

            if ($this->request->isPost()) {
                $issuer = $this->request->get('issuer', 'string');

                
            }

        }

        if (!$user) {
            return $this->redirectToLastPage();
        }

        $issuers = $paymentService->getIssuers();

        $this->view->event = $event;
        $this->view->user = $user;
        $this->view->issuers = $issuers;
    }

}
<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package EventController
 */

namespace Soul\Controller\Website;

use Phalcon\Mvc\View;
use Soul\Model\Event;
use Soul\Model\Payment;
use Soul\Payment\Data\TargetPay\IdealStart;
use Soul\Payment\Service\Exception;
use Soul\Payment\Service\TargetPay;
use Soul\Auth\Data as AuthData;
use Soul\Util;
use Soul\Security\Exception as SecurityException;

/**
 * Class EventController
 *
 * @package Soul\Controller
 */
class ArchiveController extends \Soul\Controller\Base
{
    /**
     * Show an event
     *
     * @param string $systemName Event system name
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction($systemName)
    {

        if (!$event = Event::findBySystemName($systemName)) {
            $this->flashMessage('Dit evenement bestaat niet', 'error', true);
            $this->setLastPage();

            return $this->response->redirect('/event/current');
        }

        $this->view->media = $event->getMedia();
        $this->view->pick('archive/'.$systemName);
    }


}
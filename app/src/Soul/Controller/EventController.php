<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package EventController
 */

namespace Soul\Controller;

use Phalcon\Mvc\View;
use Soul\Model\Event;
use Soul\Model\Payment;
use Soul\Payment\Data\TargetPay\IdealStart;
use Soul\Payment\Service\Exception;
use Soul\Payment\Service\TargetPay;

/**
 * Class EventController
 *
 * @package Soul\Controller
 */
class EventController extends Base
{

    /**
     * @var TargetPay
     */
    protected $paymentService;

    /**
     * Constructor
     */
    public function initialize()
    {
        parent::initialize();

        $this->paymentService = new TargetPay();
    }

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
        $transactionId = $this->request->get('trxid', 'string');

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
            $userId = $user->getUserId();
            $registered = $event->hasEntry($userId);

            // check if the user has payed for the event
            if ($registered && $event->hasPayed($userId)) {
                $payed = true;
            }
        }

        if ($transactionId) {
            try {

                if ($this->paymentService->checkTransaction($transactionId)) {
                    $this->flashMessage('Uw betaling is successvol verwerkt, bedankt! U ontvangt een bevestiging per e-mail', 'success');
                } else {
                    throw new Exception('De betaling is mislukt of geannuleerd, probeer het later nogmaals.');
                }
            } catch (Exception $e) {
                $this->flashMessage($e->getMessage(), 'error');
            }
        }

        $this->view->registered = $registered;
        $this->view->archived = ($systemName != 'current');
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

        return $this->response->redirect('event/current');
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


        $event = Event::findBySystemName($systemName);
        $user = $this->authService->getAuthData();
        $userId = $user->getUserId();
        $config = $this->getConfig();

        if ($event && $user) {

            // check if a user has payed already
            if ($event->hasPayed($userId) || !$event->hasEntry($userId)) {
                return $this->redirectToLastPage();
            }

            if ($this->request->isPost()) {
                $issuer = $this->request->get('issuer', 'string');
                $layoutCode = $config->paymentServices->targetPay->layoutCode;
                $returnUrl = $config->paymentServices->targetPay->returnUrl;
                $reportUrl = $config->paymentServices->targetPay->reportUrl;
                $description = $event->product->description;
                $amount = $event->product->cost * 100; // amount is in cents?!
                $productId = $event->productId;

                // build the transaction
                $idealStart = new IdealStart($layoutCode, $issuer, $description, $amount, $returnUrl, $reportUrl);

                // start the transaction
                $transactionDetails = $this->paymentService->startTransaction($idealStart, $userId, $productId);

                if (!$transactionDetails) {
                    $this->flashMessage('Er is iets mis gegaan met de iDeal betaling, probeer het later nogmaals', 'error', true);

                    return $this->redirectToLastPage();
                }

                if (array_key_exists('url', $transactionDetails)) {
                    return $this->response->redirect($transactionDetails['url'], true, 200);
                }

            }

        }

        if (!$user) {
            return $this->redirectToLastPage();
        }

        $issuers = $this->paymentService->getIssuers();

        $this->view->event = $event;
        $this->view->user = $user;
        $this->view->issuers = $issuers;
    }

}
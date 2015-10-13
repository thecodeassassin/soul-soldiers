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
class EventController extends \Soul\Controller\Base
{

    const DINNER_OPTION_PRICE = 10;

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
        $registered = false;
        $payed = false;
        $amountPayed = 0;
        $transactionId = $this->request->get('trxid', 'string');

        if ($systemName == 'current') {
            $event = Event::getCurrent();

            if ($event && $event->hasPassed()) {
                return $this->response->redirect('event/'.$event->systemName);
            }
        } else {

            $event = Event::findBySystemName($systemName);
            if (!$event || $event->hasPassed()) {
                return $this->response->redirect('event/current');
            }

        }

        $user = $this->authService->getAuthData();
        if ($transactionId) {
            try {

                if ($this->paymentService->checkTransaction($transactionId)) {
                    $this->flashMessage('Uw betaling is successvol verwerkt, bedankt! U ontvangt een bevestiging per e-mail', 'success');
                } else {
                    throw new Exception('De betaling is mislukt of geannuleerd, probeer het later nogmaals.');
                }
            } catch (Exception $e) {
                $this->flashMessage($e->getMessage(), 'error', true);
                $this->response->redirect(Util::getCurrentUrl(true), true, 302);
            }
        }

        if ($event && $user) {
            $userId = $user->getUserId();
            $registered = $event->hasEntry($userId);

            // check if the user has payed for the event
            if ($registered && $event->hasPayed($userId)) {
                $payed = true;
            }
        }

        if ($event) {
            $amountPayed = $event->getAmountPayed();
        }

        $this->view->registered = $registered;
        $this->view->archived = ($systemName != 'current');
        $this->view->payed = $payed;
        $this->view->amountPayed = $amountPayed;
        $this->view->event = $event;
        $this->view->media = $event->getMedia();
    }

    /**
     * @param $systemName
     *
     * @throws \Exception
     */
    public function seatAction($systemName)
    {
        $user = $this->authService->getAuthData();
        $event = Event::findBySystemName($systemName);

        $this->verifyEventAndUser($event, $user);

        $seatMap = $event->getSeatMap();

        if (!$seatMap) {

            // if there is no seat map, do not show this modal
            return;
        }

        $takenSeats = [];
        $occupiedSeats = [];
        $userSeat = 0;

        foreach ($event->entries as $eventEntry) {
            if ($eventEntry->seat != 0 && $eventEntry->seat != null) {

                if ($eventEntry->userId == $user->getUserId()) {
                    $userSeat = $eventEntry->seat;
                    continue;
                }

                $takenSeats[] = (float)$eventEntry->seat;
                $occupiedSeats[$eventEntry->seat] = $eventEntry->user->nickName;
            }
        }

        $this->view->userSeat = $userSeat;
        $this->view->event = $event;
        $this->view->takenSeats = $takenSeats;
        $this->view->occupiedSeats = $occupiedSeats;
        $this->view->seatMap = $seatMap;
        $this->view->seatingAvailable = $this->seatingAvailable($event);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

    }

    /**
     * @param $seat
     */
    public function reserveSeatAction($systemName, $reserveSeat)
    {

        try {

            $user = $this->authService->getAuthData();
            $event = Event::findBySystemName($systemName);

            $this->verifyEventAndUser($event, $user);

            if (!$this->seatingAvailable($event)) {
                throw new SecurityException('U kunt geen plek meer reserveren!');
            }

            $validSeatTable = [];
            $seatMap = $event->getSeatMap();

            $numRows = ($event->maxEntries - $event->crewSize) / $seatMap->xCount / $seatMap->yCount;

            for ($row = 1; $row <= $numRows; $row++) {
                for ($seat = 1; $seat <= $seatMap->xCount * $seatMap->yCount; $seat++) {
                    $validSeatTable[] = (float) $row.'.'.$seat;
                }
            }

            if (!in_array($reserveSeat, $validSeatTable)) {
                throw new SecurityException('Deze plaats bestaat niet!');
            }

            foreach ($event->entries as $eventEntry) {
                if ($eventEntry->seat == $reserveSeat) {
                    throw new SecurityException('U heeft geprobeerd een plek te reserveren die al is gereserveerd!');
                }
            }

            // if the seat is indeed available, save the seat to the user
            $entry = Event::findEntryByUserIdAndEventId($user->getUserId(), $event->eventId);
            $entry->seat = $reserveSeat;
            $entry->save();

            $this->flashMessage(sprintf('U heeft plek %s gereserveerd', $reserveSeat), 'success', true);

        } catch (SecurityException $e) {
            $this->flashMessage($e->getMessage(), 'error', true);
        }

        $this->response->redirect('event/current');

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
            if ($event->getAmountPayed() >= $event->maxEntries) {
                return $this->response->redirect('event/current');
            }

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

        $this->view->dinnerPrice = self::DINNER_OPTION_PRICE;


        $event = Event::findBySystemName($systemName);
        $user = $this->authService->getAuthData();
        $config = $this->getConfig();
        $dinerAvailable = false;

        // 604800 seconds is one week
        if ((strtotime($event->startDate) - 604800) > time()) {
            $dinerAvailable = true;
        }

        if ($event && $user) {
            $userId = $user->getUserId();

            // check if a user has payed already
            if ($event->hasPayed($userId) || !$event->hasEntry($userId)) {
//                return $this->redirectToLastPage();
                return $this->response->redirect('event/current');
            }

            if ($this->request->isPost()) {
                $issuer = $this->request->get('issuer', 'string');
                $layoutCode = $config->paymentServices->targetPay->layoutCode;
                $returnUrl = $config->paymentServices->targetPay->returnUrl;
                $reportUrl = $config->paymentServices->targetPay->reportUrl;
                $description = $event->product->description;
                $amount = $event->product->cost * 100; // amount is in cents?!
                $productId = $event->productId;

                if ($this->request->get('dinner_option', 'string') == 'yes') {
                    $amount = $amount + (self::DINNER_OPTION_PRICE * 100);
                    $description .= ' plus buffet';
                }

                // build the transaction
                $idealStart = new IdealStart($layoutCode, $issuer, $description, $amount, $returnUrl, $reportUrl);

                // start the transaction
                $transactionDetails = $this->paymentService->startTransaction($idealStart, $userId, $productId);

                if (!$transactionDetails) {
                    $this->flashMessage('Er is iets mis gegaan met de iDeal betaling, probeer het later nogmaals', 'error', true);

//                    return $this->redirectToLastPage();
                    return $this->response->redirect('event/current');
                }

                if (array_key_exists('url', $transactionDetails)) {
                    return $this->response->redirect($transactionDetails['url'], true, 200);
                }

            }

        }

        if (!$user) {

            $this->setLastPage(true);
            return $this->response->redirect('login');
        }

        $issuers = $this->paymentService->getIssuers();

        $this->view->event = $event;
        $this->view->user = $user;
        $this->view->issuers = $issuers;
        $this->view->dinerAvailable = $dinerAvailable;
    }

    /**
     * @param Event    $event
     * @param AuthData $user
     *
     * @throws \Exception
     */
    protected function verifyEventAndUser(Event $event, AuthData $user)
    {
        if (!$event) {
            throw new \Exception(sprintf('Event %s not found', $event->systemName));
        }

        $entry = $event->hasEntry($user->userId);

        if (!$entry) {
            throw new \Exception(sprintf('User %s doesn\'t have an entry', $user->getNickName()));
        }

        if (!$event->hasPayed($user->userId)) {
            throw new \Exception(sprintf('User %s did not pay yet', $user->getNickName()));
        }

    }

    /**
     * Check if seating is still available for this event
     *
     * @param Event $event
     *
     * @return bool
     */
    protected function seatingAvailable(Event $event)
    {
        return  ((strtotime($event->startDate) - 3600) > time());
    }


}
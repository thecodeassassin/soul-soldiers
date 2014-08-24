<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;

use Phalcon\Mvc\View;
use Soul\AclBuilder;
use Soul\Form\EditUserForm;
use Soul\Model\Event;
use Soul\Model\Payment;
use Soul\Model\User;
use Soul\Util;

/**
 * Class AdminController
 *
 * @package Soul\Controller
 */
class AdminController extends \Soul\Controller\Base
{
    public function initialize()
    {
        parent::initialize();

    }

    /**
     *
     */
    public function indexAction()
    {
        $this->view->page = 'index';
    }

    public function massMailAction()
    {
        $this->view->page = 'massmail';
    }

    public function usersAction()
    {
        $this->view->page = 'users';

        $event = Event::getCurrent();

        $users = User::find();

        $this->view->event = $event;
        $this->view->users = $users;
    }


    public function deleteUserAction($userId)
    {
        $user = $this->validateUserId($userId);

        if (!$user) {
            $this->flashMessage('Ongeldige gebruiker', 'error', true);
        }

        if ($user->userType == AclBuilder::ROLE_ADMIN) {
            $this->flashMessage('Admins kunnen niet verwijderd worden (sorry :P)', 'error', true);
        } else {
            $user->delete();
            $this->flashMessage(sprintf('Gebruiker %s verwijderd', $user->nickName), 'success', true);
        }

        $this->response->redirect('admin/users');
    }

    /**
     * @param $userId
     */
    public function editUserAction($userId)
    {

        $user = $this->validateUserId($userId);
        $event = Event::getCurrent();
        $editUserForm = new EditUserForm($user);
        $hasPayed = $event->hasPayed($userId);
        $hasEntry = (bool)$event->hasEntry($userId);
        $payedForBuffet = ($hasPayed && $event->getUserPayment($userId) >= ($event->getEventCost() + EventController::DINNER_OPTION_PRICE) ? true : false);

        if (!$user) {
            return false;
        }

        $this->view->form = $editUserForm;
        $this->view->page = 'users';
        $this->view->registered = $hasEntry;
        $this->view->payed = $hasPayed;
        $this->view->userId = $userId;
        $this->view->payedForBuffet = $payedForBuffet;

        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            // bind the user to the formData
            $editUserForm->bind($post, $user);

            if ($this->request->isPost()) {
                if ($editUserForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($editUserForm->getMessages(), 'error', true);
                } else {

                    // if validation fails, show messages
                    if ($user->validation() === false) {

                        $this->flashMessages($user->getMessages(), 'error');
                    } else {

                        $formPayed = $this->request->get('payed');
                        $buffetSelected = $this->request->get('buffet');
                        $entry = $event->hasEntry($userId);

                        if (!$hasPayed && $formPayed || ($hasPayed && $buffetSelected)) {

                            if (!$hasPayed) {
                                $paymentReference = $this->request->get('paymentReference');

                                if(!$paymentReference) {
                                    $this->flashMessage('Betaling referentie is verplicht!', 'error', true);
                                    return false;
                                }

                                $amount = $event->getEventCost();

                                // if the buffet option has been selected, add it
                                if ($buffetSelected && !$payedForBuffet) {
                                    $amount += EventController::DINNER_OPTION_PRICE;
                                }

                                if(!$newPayment = Payment::createPayment($amount, $paymentReference, $userId, $event->productId)) {
                                    $this->flashMessage('Registreren van betaling mislukt', 'error', true);
                                }

                                $newPayment->confirmed = 1;
                                $newPayment->save();

                                $entry->paymentId = $newPayment->paymentId;
                                $entry->save();

                                // confirm the payment with the user
                                $this->getMail()->sendToUser(
                                    User::findFirstByUserId($userId),
                                    'Bedankt voor je betaling',
                                    'paymentConfirmed',
                                    compact('event')
                                );


                            } else {

                                if ($buffetSelected) {

                                    $payment = $entry->payment;

                                    $payment->amount += EventController::DINNER_OPTION_PRICE;
                                    $payment->save();
                                }
                            }

                        }

                        // save the user
                        $user->save();
                        $this->flashMessage(sprintf('Gebruiker %s succesvol aangepast', $user->nickName), 'success', true);

                        $this->response->redirect('admin/edituser/'.$userId);
                    }

                }
            }

        }


    }

    /**
     * @param $userId
     *
     * @return null|User
     */
    protected function validateUserId($userId)
    {
        $userId = $this->filter->sanitize($userId, 'int');
        $user = null;

        if (!$userId) {
            $this->flashMessage('Ongeldige gebruiker id', 'error', true);
        } else {
            $user = User::findFirstByUserId($userId);
        }

        return $user;
    }
}
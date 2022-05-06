<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;

use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Soul\AclBuilder;
use Soul\Form\EditUserForm;
use Soul\Form\MassMailForm;
use Soul\Model\Event;
use Soul\Model\Payment;
use Soul\Model\StaticModel\DataList;
use Soul\Model\StaticModel\Exception as StaticModelException;
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
        $event = Event::getCurrent();

        if ($event) {

            $submitted = false;
            $massmailForm = new MassMailForm(null, $event->getUsers());

            if ($this->request->isPost()) {

                $emailString = $this->request->getPost('emails');
                $emailContent = $this->request->getPost('content', 'string');
                $subject = $this->request->getPost('subject', 'string');
                preg_match_all("/\<(.+?)\>(.+?);/", $emailString, $emailAddresses);

                // if the form is invalid, show the messages to the user
                if ($massmailForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($massmailForm->getMessages(), 'error');

                } else {
                    $submitted = true;

                    if (count($emailAddresses[1]) > 0 && count($emailAddresses[2]) > 0) {

                        $emails = [];

                        for($i=0;$i<count($emailAddresses[1]);$i++) {
                            $emails[trim($emailAddresses[2][$i])] = trim($emailAddresses[1][$i]);
                        }

                        $user = null;

                        // if the form is valid, inform the adminEmail
                        $this->getMail()->send(
                        $emails,
                        $subject,
                        'massmail',
                        compact('subject', 'emailContent', 'user'));
                    } else {
                        $this->flashMessage('Ongeldige email adressessen', 'error');
                    }

                }

            }

            $this->view->submitted = $submitted;
            $this->view->form = $massmailForm;
            $this->view->page = 'massmail';
        }
    }

    public function usersAction()
    {
        $this->view->page = 'users';
        $search = $this->request->get('search', 'string', null);

        $event = Event::getCurrent();

        if ($search != null) {
            $users = User::find([
                "conditions" => "nickName LIKE :query:
                                 OR realName LIKE :query:
                                 OR address LIKE :query:
                                 OR city LIKE :query:
                                 OR email LIKE :query:",
                "bind"       => ['query' => '%'.$search.'%']
            ]);
        } else {
            $users = User::find();
        }

        $this->view->event = $event;
        $this->view->users = $users;
        $this->view->search = $search;
    }

    /**
     * @param null $selected
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function listsAction($selected = null)
    {
        $availableLists = DataList::getAvailableListsStatic();

        try {

            if ($selected) {
                // get the list and offer the download
                $list = new DataList($selected);

                $csv = $list->outputAsCSV();

                $response = new Response();
                $response->setContentType('text/csv');
                $response->setHeader("Content-Disposition", "attachment; filename=$selected.csv");
                $response->setHeader("Pragma", "no-cache");
                $response->setHeader("Expires", "0");
                $response->setExpires(new \DateTime('now'));

                $response->setContent($csv);

                return $response->send();
            }

        } catch(StaticModelException $e) {
            $this->flashMessage(sprintf("Download of list failed: %s", $e->getMessage()), 'error');
        }

        $this->view->availableLists = $availableLists;
        $this->view->page = 'lists';

    }

    public function deleteUserAction($userId)
    {
        $user = $this->validateUserId($userId);

        if (!$user) {
            $this->flashMessage('Ongeldige gebruiker', 'error');
        }

        if ($user->userType == AclBuilder::ROLE_ADMIN) {
            $this->flashMessage('Admins kunnen niet verwijderd worden (sorry :P)', 'error');
        } else {
            $user->delete();
            $this->flashMessage(sprintf('Gebruiker %s verwijderd', $user->nickName), 'success');
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
        $payedForBuffet = $event->hasPayedForBuffet($userId);

        if (!$user) {
            return false;
        }

        $this->view->form = $editUserForm;
        $this->view->page = 'users';
        $this->view->registered = $hasEntry;
        $this->view->payed = $hasPayed;
        $this->view->userId = $userId;
        $this->view->payedForBuffet = $payedForBuffet;

        try {
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
                                        throw new \Exception('Betaling referentie is verplicht!');
                                    }

                                    $amount = $event->getEventCost();

                                    // if the buffet option has been selected, add it
                                    if ($buffetSelected && !$payedForBuffet) {
                                        $amount += EventController::DINNER_OPTION_PRICE;
                                    }

                                    if(!$newPayment = Payment::createPayment($amount, $paymentReference, $userId, $event->productId)) {
                                        throw new \Exception('Registreren van betaling mislukt');
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
        } catch (\Exception $e) {
            $this->flashMessage($e->getMessage(), 'error');
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
            $this->flashMessage('Ongeldige gebruiker id', 'error');
        } else {
            $user = User::findFirstByUserId($userId);
        }

        return $user;
    }
}
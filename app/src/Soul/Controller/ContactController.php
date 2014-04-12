<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller;

use Soul\Form\ContactForm;
use Soul\Model\User;

/**
 * Class ContactController
 *
 * @package Soul\Controller
 */
class ContactController extends Base
{
    /**
     *
     */
    public function indexAction()
    {
        $contactForm = new ContactForm();
        $submitted = false;
        $user = $this->authService->getAuthData();

        if ($this->request->isPost()) {

            $userEmail = $this->request->getPost('email', 'email');
            $userFullName = $this->request->getPost('realName', 'string');
            $emailContent = $this->request->getPost('content', 'string');

            // if the form is invalid, show the messages to the user
            if ($contactForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($contactForm->getMessages(), 'error');

            } else {
                $submitted = true;

                // if the form is valid, inform the adminEmail
                $this->getMail()->send(
                    [
                        $this->config->mail->adminAddress => $this->config->mail->adminAddress
                    ],
                    'Reactie van website',
                    'contact',
                    compact('userEmail', 'userFullName', 'emailContent')
                );

            }

        } else {
            if ($user) {
                $userModel = User::findFirstByUserId($user->getUserId());

                $contactForm->setEntity($userModel);
            }
        }

        $this->view->submitted = $submitted;
        $this->view->form = $contactForm;
    }
}
<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller;

use Soul\Form\ContactForm;

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

        try {
            if ($this->request->isPost()) {
                $email = $this->request->getPost('email', 'email');
                $realname = $this->request->getPost('realName', 'string');
                $emailContent = $this->request->getPost('content', 'string');

                // if the form is invalid, show the messages to the user
                if ($contactForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($contactForm->getMessages(), 'error');

                } else {
                    $submitted = true;

                    // if the form is valid, inform the adminEmail
                    $this->getMail()->send([

                    ])

                }

            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }


        $this->view->submitted = $submitted;
        $this->view->form = $contactForm;
    }
}
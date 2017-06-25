<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;

use Soul\Form\ContactForm;
use Soul\Model\User;
use Soul\Util;

/**
 * Class ContactController
 *
 * @package Soul\Controller
 */
class ContactController extends \Soul\Controller\Base
{
    /**
     *
     */
    public function indexAction()
    {
        $contactForm = new ContactForm();
        $submitted = false;
        $user = $this->authService->getAuthData();
        $siteKey = $this->config->captcha->siteKey;
        $secretKey = $this->config->captcha->secretKey;
        
        if ($this->request->isPost()) {

            $userEmail = $this->request->getPost('email', 'email');
            $userFullName = $this->request->getPost('realName', 'string');
            $emailContent = $this->request->getPost('content', 'string');
            $captchaResponse = $this->request->getPost('g-recaptcha-response', 'string');
            $messages = $contactForm->getMessages();
            $captchaFailed = false;
          
            
            $recaptcha = new \ReCaptcha\ReCaptcha($secretKey);
            $resp = $recaptcha->verify($captchaResponse, Util::getClientIp());
            if (!$resp->isSuccess()) {
                $captchaFailed = true;
                $messages->appendMessage(
                    new \Phalcon\Validation\Message('Captcha niet geldig')
                );
            }

            // if the form is invalid, show the messages to the user
            if ($captchaFailed || $contactForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($messages, 'error');

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
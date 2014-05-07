<?php

namespace Soul\Controller;

use Phalcon\Mvc\View;
use Soul\Controller\Base;
use Soul\Form\AccountInformationForm;
use Soul\Form\ChangePasswordForm;
use Soul\Form\ForgotPasswordForm;
use Soul\Form\LoginForm;
use Soul\Model\FailedAttempt;
use Soul\Model\User;


/**
 * Class AccountBase
 *
 * @package Soul\Controller
 */
class AccountBase extends Base
{


    /**
     * @return string
     */
    public function getCsrf()
    {
        return $this->security->getToken();
    }


    /**
     *
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     *
     */
    public function loginAction()
    {

        $loginForm = new LoginForm();

        if ($this->authService->getAuthData() && !$this->request->isPost()) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            return $this->response->redirect('home');
        }

        try {
            if ($this->request->isPost()) {
                $email = $this->request->getPost('email', 'email');
                $password = $this->request->getPost('password', 'string');

                // if the form is invalid, show the messages to the user
                if ($loginForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($loginForm->getMessages(), 'error');

                } else {
                    $authUser = User::authenticate($email, $password);

                    if ($authUser->state == User::STATE_ACTIVE && $authUser->confirmKey != null) {
//                      return $this->response->redirect('/change-password');
                    }

                    $this->flashMessage('Je bent nu ingelogd.', 'success', true);

                    // redirect the user to his last known location
                    if ($this->getLastPage()) {
                        return $this->redirectToLastPage();
                    }


                    return $this->response->redirect('home');
                }

            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $loginForm;
    }


    /**
     * Logout a user
     */
    public function logoutAction()
    {
        $this->authService->destroyAuthData();
        $this->response->redirect('home');
    }

    /**
     * User forgot his password
     */
    public function forgotPasswordAction()
    {
        $forgotPasswordForm = new ForgotPasswordForm();


        if ($this->request->isPost()) {

            $email = $this->request->get('email', 'email');

            if ($forgotPasswordForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($forgotPasswordForm->getMessages(), 'error');
            } else {

                // if validation fails, show messages
                if ($user = User::findFirstByEmail($email)) {

                    try {

                        FailedAttempt::add($email, true);

                        // send the user a confirmation email and save the user
                        $this->authService->sendForgotPasswordMail($user);
                        $user->save();

                        $this->flashMessage('Er is een e-mail naar u gestuurd met instructies om uw wachtwoord te wijzigen',  'success', true);

                        return $this->response->redirect('home');

                    } catch (AuthException $e) {
                        $this->flashMessage('U heeft dit formulier te vaak geprobeerd te versturen, probeer het later nogmaals.', 'error');
                    }

                } else {
                    $this->flashMessage('Dit e-mail adres is niet bij ons bekend.', 'error');
                }

            }
        }

        $this->view->form = $forgotPasswordForm;
    }

    public function manageAction()
    {
        $auth = $this->authService->getAuthData();
        $post = $this->request->getPost();

        $user = User::findFirstByUserId($auth->getUserId());

        if (!$user) {
            throw new \Exception(sprintf('User with id %s cannot be found.', $auth->getUserId()));
        }

        $changepasswordForm = new ChangePasswordForm();
        $changepasswordForm->addCurrentPassword();
        $changepasswordForm->remove('csrf');

        $accountInformationForm = new AccountInformationForm();
        $accountInformationForm->setEntity($user);

        if ($this->request->isPost()) {

            if (array_key_exists('profile-form', $post)) {


                $accountInformationForm->bind($post, $user);
                if ($accountInformationForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($accountInformationForm->getMessages(), 'error');
                } else {

                    // if validation fails, show messages
                    if ($user->validation() === false) {

                        $this->flashMessages($user->getMessages(), 'error');
                    } else {
                        // create the new user
                        $user->save();

                        $this->flashMessage('Uw profiel informatie is gewijzigd', 'success');

                    }
                }
            }

            if (array_key_exists('password-form', $post)) {
                $currentPassword = $this->request->get('currentPassword', 'string');
                $newPassword = $this->request->get('password', 'string');

                if ($user->password !== sha1($currentPassword)) {
                    $this->flashMessage('Het huidige wachtwoord wat u heeft opgegeven klopt niet.', 'error', true);
                    $this->response->redirect('account/manage#change-password');

                } else {
                    if ($changepasswordForm->isValid($this->request->getPost()) == false) {
                        $this->flashMessages($changepasswordForm->getMessages(), 'error', true);
                        $this->response->redirect('account/manage#change-password');
                    } else {

                        // form is valid, change the password
                        $user->changePassword($newPassword, false);


                        $this->flashMessage('Uw wachtwoord is gewijzigd', 'success', true);

                        $this->response->redirect('account/manage#change-password');
                    }
                }
            }
        }

        $this->view->userObject = $user;
        $this->view->chpass = $changepasswordForm;
        $this->view->form = $accountInformationForm;
    }
}
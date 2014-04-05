<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller;

use Soul\AclBuilder;
use Soul\Auth\AuthService as AuthService;
use Soul\Form\AccountInformationForm;
use Soul\Form\ChangePasswordForm;
use Soul\Form\ForgotPasswordForm;
use Soul\Form\LoginForm;
use Soul\Form\RegistrationForm;
use Soul\Model\FailedAttempt;
use Soul\Model\User;
use Soul\Auth\Exception as AuthException;
use Soul\Auth\Data as AuthData;
use Soul\Util;

/**
 * Class Account
 * @package Soul\Controller
 */
class AccountController extends Base
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

        $this->setLastPage();
    }

    /**
     *
     */
    public function loginAction()
    {

        $loginForm = new LoginForm();

        if ($this->authService->getAuthData() && ! $this->request->isPost()) {
//            return $this->redirectToLastPage();
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
//                    return $this->redirectToLastPage();
                    return $this->response->redirect('home');
                }

            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $loginForm;
    }

    /**
     * Registration for new users
     */
    public function registerAction()
    {
        $registrationForm = new RegistrationForm();

        if ($this->authService->getAuthData() && ! $this->request->isPost()) {
//            return $this->redirectToLastPage();
            return $this->response->redirect('home');
        }

        $post = $this->request->getPost();
        $newUser = new User();

        $registrationForm->bind($post, $newUser);

        if ($this->request->isPost()) {
            if ($registrationForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($registrationForm->getMessages(), 'error');
            } else {

                // if validation fails, show messages
                if ($newUser->validation() === false) {

                    $this->flashMessages($newUser->getMessages(), 'error');
                } else {

                    // send the user a confirmation email
                    $this->authService->sendConfirmationMail($newUser);

                    // create the new user
                    $newUser->save();

                    $this->flashMessage('Je registratie is gelukt, hou je e-mail in de gaten voor een bevestigings e-mail.', 'success', true);

//                    return $this->redirectToLastPage();
                    return $this->response->redirect('home');

                }

            }
        }

        $this->view->form = $registrationForm;
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
     * Confirm the user by confirmKey
     *
     * @param string $confirmKey Unique confirmation key
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function confirmUserAction($confirmKey)
    {

        if ($user = User::findFirstByConfirmKey($confirmKey)) {

            // confirm the user
            $user->confirm();

            // make sure the user is also logged in
            $this->authService->setAuthData(AuthData::buildFromUser($user));
            $this->flashMessage('Uw account is geactiveerd, u bent nu ingelogd.', 'success', true);

            return $this->response->redirect('home');
        }

        $this->flashMessage('Uw account kan niet worden geactiveerd, neem a.u.b. contact met ons.', 'error', true);
        return $this->response->redirect('home');

    }

    /**
     * Change password after reset mail has been send
     *
     * @param null $confirmKey
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function changePasswordAction($confirmKey = null)
    {

        $user = User::findFirstByConfirmKey($confirmKey);

        if (!$user) {
//            return $this->redirectToLastPage();
            return $this->response->redirect('home');
        }

        // reset failed attempts
        FailedAttempt::reset();

        $changePasswordForm = new ChangePasswordForm();

        if ($this->request->isPost()) {

            $password = $this->request->get('password', 'string');

            if ($changePasswordForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($changePasswordForm->getMessages(), 'error');
            } else {

                $user->changePassword($password);

                // make sure the user is also logged in
                $this->authService->setAuthData(AuthData::buildFromUser($user));

                $this->flashMessage('Uw wachtwoord is gewijzigd, u bent nu ingelogd', 'success', true);
                return $this->response->redirect('home');
//                return $this->redirectToLastPage();


            }
        }


        $this->view->form = $changePasswordForm;
        $this->view->confirmKey = $confirmKey;

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

    /**
     * Resend a confirmation email
     *
     * @param $userId
     */
    public function resendConfirmationAction($userId)
    {

        $this->view->disable();
        try {
            $userId = Util::decodeUrlSafe($userId);

            if ($user = User::findFirstByUserId($userId)) {

                $this->authService->sendConfirmationMail($user, true);

                $this->flashMessage('Bevestigings email opnieuw verstuurd.', 'success', true);
                return $this->response->redirect('home');
            }

        } catch (\Exception $e) {
            $this->flashMessage('Bevestigings email kon niet worden vestuurd, gebruiker niet gevonden.', 'error', true);
        }

        return $this->response->redirect('home');

    }

    /**
     * @param $email
     */
    public function unsubscribeAction($email)
    {

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

        $this->view->user = $user;
        $this->view->chpass = $changepasswordForm;
        $this->view->form = $accountInformationForm;
    }
}
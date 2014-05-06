<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller\Website;

use Soul\AclBuilder;
use Soul\Auth\AuthService as AuthService;
use Soul\Controller\AccountBase;
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
class AccountController extends AccountBase
{

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


}
<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller;

use Soul\AclBuilder;
use Soul\Auth\Service as AuthService;
use Soul\Form\LoginForm;
use Soul\Form\RegistrationForm;
use Soul\Model\User;
use Soul\Auth\Exception as AuthException;

/**
 * Class Account
 * @package Soul\Controller
 */
class AccountController extends Base
{

    /**
     * @var AuthService
     */
    protected $authService = null;


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

        $this->authService = $this->di->get('auth');

        $this->setLastPage();
        die;
    }

    /**
     *
     */
    public function loginAction()
    {

        $loginForm = new LoginForm();

        try {
            if ($this->request->isPost()) {
                $email = $this->request->getPost('email', 'email');
                $password = $this->request->getPost('password', 'string');

                // if the form is invalid, show the messages to the user
                if ($loginForm->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($loginForm->getMessages(), 'error');

                } else {
                    $authUser = User::authenticate($email, $password);

                    if ($authUser->state == User::STATE_REQUIRES_PASSWORD_CHANGE) {
                        $this->response->redirect('/change-password');
                    }

                    // redirect the user to his last known location
                    $this->redirectToLastPage();
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

                    $confirmLink = $this->authService->generateConfirmationLink($newUser);

                    // send the user a confirmation email
                    $this->getMail()->sendToUser(
                        $newUser,
                        'Bevestig je e-mail adres',
                        'confirmEmail',
                        compact('confirmLink')
                    );

                    // create the new user
                    $newUser->save();

                    $this->flashMessage('Je registratie is gelukt, hou je e-mail in de gaten voor een bevestigings e-mail.', 'success');
                    $this->redirectToLastPage();

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
        $this->redirectToLastPage();
    }
}
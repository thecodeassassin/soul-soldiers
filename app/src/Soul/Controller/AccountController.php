<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller;

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
    }

    /**
     *
     */
    public function loginAction()
    {

        $form = new LoginForm();

        try {
            if ($this->request->isPost()) {
                $email = $this->request->getPost('email', 'email');
                $password = $this->request->getPost('password', 'string');

                // if the form is invalid, show the messages to the user
                if ($form->isValid($this->request->getPost()) == false) {
                    $this->flashMessages($form->getMessages(), 'error');

                } else {
                    User::authenticate($email, $password);

                    // redirect the user to his last known location
                    $this->redirectToLastPage();
                }

            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Registration for new users
     */
    public function registerAction()
    {
        $registrationForm = new RegistrationForm();

        
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
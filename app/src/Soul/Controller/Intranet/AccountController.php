<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;
use Soul\Controller\AccountBase;
use Soul\Form\ChangePasswordForm;
use Soul\Form\RegistrationForm;
use Soul\Model\FailedAttempt;
use Soul\Model\User;
use Soul\Auth\Data as AuthData;
use Soul\Util;

/**
 * Class Account
 * @package Soul\Controller
 */
class AccountController extends AccountBase
{


    public function loginAction()
    {
        $this->view->setMainView('layouts/login');

        parent::loginAction();
    }

    public function manageAction()
    {
        parent::manageAction();

        $this->view->partial('../Website/account/manage');
        $this->view->pageTitle = '<i class="icon-user-1"></i> Mijn account';
    }

    public function forgotPasswordAction()
    {

        parent::forgotPasswordAction();

        $this->view->setMainView('layouts/login');

    }

}
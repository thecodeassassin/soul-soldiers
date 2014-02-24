<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController 
 */  

namespace Soul\Controller;

use Soul\Auth\Service as Auth;

class Account extends Base
{

    /**
     * @var Auth
     */
    protected $authService = null;

    static $lastReferer = null;

    public function initialize()
    {
        parent::initialize();

        $this->authService = $this->di->get('auth');

//        if ($this->request->getHTTPReferer())
        var_dump($this->request->getHTTPReferer());
    }

    public function loginAction()
    {

        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password', 'string');

            $validUser = $this->authService->check($email, $password);

            if ($validUser) {
                die('YES');
            } else {
                die('no');
            }


        }
    }
} 
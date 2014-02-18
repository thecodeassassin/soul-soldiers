<?php
/**
 * File: AuthService.php
 * @author Stephen Hoogendijk
 *
 */

namespace Soul\Auth;

use Phalcon\Session\AdapterInterface as Session;
use Soul\Module;
use Soul\ServiceBase;

/**
 * Class AuthService
 * @package Soul\Auth
 */
class Service extends ServiceBase
{

    /**
     * @var Session
     */
    protected $session;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setSession($this->di->get('session'));
    }

    /**
     * Returns true when the user has a session
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->session->has('auth')) {
            return true;
        }

        return false;
    }

    /**
     * @return Data
     */
    public function getAuthData()
    {
        if ($this->isLoggedIn()) {
            return $this->session->get('auth');
        }

        return null;
    }


    /**
     * Returns the user's type
     *
     * @return int
     */
    public function getUserType()
    {
        if ($authData = $this->getAuthData()) {
            return $authData->getUserType();
        }
    }

    /**
     * @param Session $session The session to set
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
}
<?php
/**
 * File: AuthService.php
 * @author Stephen Hoogendijk
 *
 */

namespace Soul\Auth;

use Phalcon\Session\AdapterInterface as Session;
use Soul\Model\User;
use Soul\Module;
use Soul\ServiceBase;
use Soul\Util;

/**
 * Class Service
 *
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
            return unserialize($this->session->get('auth'));
        }

        return null;
    }

    /**
     * Set authentication data
     *
     * @param \Soul\Auth\Data $data Authentication data
     */
    public function setAuthData(Data $data)
    {
        $this->session->set('auth', serialize($data));
    }

    /**
     *
     */
    public function destroyAuthData()
    {
        $this->session->remove('auth');
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
     * Checks for a valid username / password combination
     *
     * Returns the user upon successful authentication
     *
     * @param string $email    User's email
     * @param string $password User's password
     *
     * @return bool|User
     */
    public function check($email, $password)
    {
        $userAccount = User::findFirstByEmail($email);

        if ($userAccount) {
            if ($userAccount->password == sha1($password)) {
                return $userAccount;
            }
        };

        return false;
    }

    /**
     * Generate a confirmation link for a user
     *
     * Also updates the user's confirmKey
     *
     * @param User $user
     *
     * @return string
     */
    public function generateConfirmationLink(User $user)
    {
        $uniqueKey = sha1(uniqid());
        $user->confirmKey = $uniqueKey;
        $user->save();

        return $this->url->get('confirm-user/'.$uniqueKey);
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
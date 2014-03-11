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
 * Class AuthService
 *
 * @package Soul\Auth
 */
class AuthService extends ServiceBase
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
     * Does not save the user!
     *
     * @param User   $user       user instance
     * @param bool   $useLastKey use last known key
     * @param string $path       Path to generate
     *
     * @return string
     */
    public function generateConfirmationLink(User $user, $useLastKey = false, $path = 'confirm-user/')
    {
        if ($useLastKey) {
            $uniqueKey = $user->confirmKey;
        } else {
            $uniqueKey = sha1(uniqid());
            $user->confirmKey = $uniqueKey;
        }

        return $this->url->get($path.$uniqueKey);
    }

    /**
     * Send a confirmation mail to the given user
     *
     * @param User $user       user instance
     * @param bool $useLastKey use last known key
     *
     * @return mixed
     */
    public function sendConfirmationMail(User $user, $useLastKey = false)
    {
        $confirmLink = $this->generateConfirmationLink($user, $useLastKey);

        return $this->getMail()->sendToUser(
            $user,
            'Bevestig je e-mail adres',
            'confirmEmail',
            compact('confirmLink')
        );
    }

    /**
     * @param User $user
     */
    public function sendForgotPasswordMail(User $user)
    {
        $confirmLink = $this->generateConfirmationLink($user, false, 'change-password/');

        return $this->getMail()->sendToUser(
            $user,
            'Wachtwoord vergeten',
            'forgotPassword',
            compact('confirmLink')
        );
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
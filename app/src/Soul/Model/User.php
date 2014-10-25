<?php
namespace Soul\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Soul\AclBuilder;
use Soul\Auth\Exception;
use Soul\Auth\AuthService as AuthService;
use Soul\Auth\Data as AuthData;
use Soul\Util;

/**
 * Class User
 * @package Soul\Model
 */
class User extends Base
{

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $nickName;

    /**
     *
     * @var string
     */
    public $realName;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $postalCode;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var integer
     */
    public $userType;

    /**
     *
     * @var string
     */
    public $confirmKey;

    /**
     *
     * @var string
     */
    public $isActive;

    /**
     * @var integer
     */
    public $state;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_REQUIRES_PASSWORD_CHANGE = 2;

    const STATE_BANNED = 3;

    /**
     * Email update mode
     *
     * @var bool
     */
    protected $emailUpdate = false;

    /**
     * Validations and business logic
     *
     * @return bool
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    "field"    => "email",
                    "required" => true,
                )
            )
        );

        if (!$this->userId || $this->emailUpdate) {

            $this->validate(new Uniqueness(
                array(
                    "field"   => "email",
                    "message" => sprintf("Er bestaat al een account met email adres '%s'", $this->email)
                )
            ));

        }

        $existing = self::findFirstByUserId($this->userId);

        if ($existing->nickName != $this->nickName) {
            $this->validate(new Uniqueness(
                array(
                    "field"   => "nickName",
                    "message" => "Deze nickname is reeds gekozen"
                )
            ));
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * @param $stateId
     *
     * @return string|bool
     */
    public function getUserState() {
        switch($this->state) {
            case self::STATE_REQUIRES_PASSWORD_CHANGE:
                return 'Requires password change';
            case self::STATE_INACTIVE:
                return 'Inactive';
            case self::STATE_ACTIVE:
                return 'Actief';
            case self::STATE_BANNED:
                return 'Banned';
            default:
                return false;

        }
    }

    /**
     * @param bool $mode
     */
    public function setEmailUpdateMode($mode)
    {
        $this->emailUpdate = (bool)$mode;
    }

    /**
     * @return bool|string
     */
    public function getUserType()
    {
        switch($this->userType) {
            case AclBuilder::ROLE_ADMIN:
                return 'Admin';
            case AclBuilder::ROLE_GUEST:
                return 'Guest';
            case AclBuilder::ROLE_MODERATOR:
                return 'Moderator';
            case AclBuilder::ROLE_USER:
                return 'User';
            default:
                return false;

        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblUser');
    }

    /**
     * Set some default values
     */
    public function beforeValidationOnCreate()
    {

        // set default values
        $this->state = self::STATE_INACTIVE;
        $this->isActive = 1;
        $this->userType = AclBuilder::ROLE_USER;
        $this->password = sha1($this->password);
    }

    /**
     * Authenticate a user
     *
     * @param string $email    user's email
     * @param string $password user's password
     *
     * @throws \Soul\Auth\Exception
     * @return User
     */
    public static function authenticate($email, $password)
    {
        // add a login attempt
        FailedAttempt::add($email);

        $authService = new AuthService();

        $authUser = $authService->check($email, $password);
        if ($authUser instanceof self) {

            if ($authUser->state == User::STATE_BANNED) {
                throw new Exception('Je bent gebanned, je kunt niet meer inloggen met deze account');
            }

            if (!in_array($authUser->state, [User::STATE_ACTIVE, User::STATE_REQUIRES_PASSWORD_CHANGE])) {
                $resendLink = sprintf(
                    '<a href="%s/%s">hier</a>',
                    BASE_URL.'/resend-confirmation',
                    Util::encodeUrlSafe($authUser->userId)
                );

                throw new Exception(sprintf(
                    'Dit account is niet actief, u dient uw account te activeren. Klik %s
                                     om de activatiemail opnieuw te versturen.', $resendLink));
            }

            $authService->setAuthData(AuthData::buildFromUser($authUser));

            FailedAttempt::resetAll();

            return $authUser;
        }

        throw new Exception('E-mail en/of wachtwoord is ongeldig');
    }

    /**
     * Confirm the user
     */
    public function confirm()
    {
        $this->confirmKey = null;
        $this->state = self::STATE_ACTIVE;
        $this->save();
    }


    /**
     * Change the user's password
     */
    public function changePassword($password, $setConfirmKey = true)
    {
        if ($setConfirmKey) {
            $this->confirmKey = null;
        }
        $this->password = sha1($password);
        $this->save();
    }

    /**
     * @param $userId
     * @return User
     */
    public static function findFirstByUserId($userId)
    {
        return self::findFirst($userId);
    }

    /**
     * Independent Column Mapping.
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'userId' => 'userId',
            'email' => 'email',
            'password' => 'password',
            'nickName' => 'nickName',
            'realName' => 'realName',
            'address' => 'address',
            'postalCode' => 'postalCode',
            'city' => 'city',
            'telephone' => 'telephone',
            'userType' => 'userType',
            'confirmKey' => 'confirmKey',
            'isActive' => 'isActive',
            'state' => 'state'
        );
    }

}

<?php
namespace Soul\Model;

use Phalcon\Mvc\Model\Validator\Email as Email;
use Soul\Auth\Exception;
use Soul\Auth\Service as AuthService;
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
        if ($this->validationHasFailed() == true) {
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
     * Run forgot password routine
     * returns true if the password was reset and mailed to the user
     *
     * @return bool
     */
    public function forgotPassword()
    {

        // set the password to a temporary one and email the user with this temporary password
        if ($this->state == self::STATE_ACTIVE) {
            $this->password = Util::generateRandomPassword();
            $this->state = self::STATE_REQUIRES_PASSWORD_CHANGE;

            // todo mail user

            return true;
        }

        return false;
    }

    /**
     * Authenticate a user
     *
     * @param string $email    user's email
     * @param string $password user's password
     *
     * @return bool
     */
    public static function authenticate($email, $password)
    {
        // add a login attempt
        FailedAttempt::add($email);

        $authService = new AuthService();

        $authUser = $authService->check($email, $password);
        if ($authUser instanceof self) {
            $authService->setAuthData(AuthData::buildFromUser($authUser));

            return true;
        }

        Throw new Exception('E-mail en/of wachtwoord is ongeldig');
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
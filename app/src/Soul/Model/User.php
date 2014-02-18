<?php
namespace Soul\Model;

use Phalcon\Mvc\Model\Validator\Email as Email;

/**
 * Class User
 * @package Soul\Model
 */
class User extends BaseModel
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
     * Validations and business logic
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
     * Independent Column Mapping.
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
            'isActive' => 'isActive'
        );
    }

}

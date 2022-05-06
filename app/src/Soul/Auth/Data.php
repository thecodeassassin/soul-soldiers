<?php

/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Auth;

use Soul\AclBuilder;
use Soul\Model\User;

/**
 * Authentication data
 *
 * Class Data
 * @package Soul\Auth
 */
class Data
{
    public $userId = null;
    public $nickName = null;
    public $email = null;
    public $realName = null;
    public $userType = null;
    public $userState = null;


    /**
     * @param string $email
     *
     * @return Data
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $nickName
     *
     * @return Data
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @param string $realName
     *
     * @return Data
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param int $userId
     *
     * @return Data
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userType
     *
     * @return Data
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param null $userState
     *
     * @return Data
     */
    public function setUserState($userState)
    {
        $this->userState = $userState;

        return $this;
    }

    /**
     * @return null
     */
    public function getUserState()
    {
        return $this->userState;
    }

    /**
     * Build user data object from user
     *
     * @param User $user user object
     *
     * @return \Soul\Auth\Data
     */
    public static function buildFromUser(User $user)
    {
        $instance = new self();

        $instance->setEmail($user->email)
            ->setUserId($user->userId)
            ->setUserType($user->userType)
            ->setNickName($user->nickName)
            ->setUserState($user->state)
            ->setRealName($user->realName);

        return $instance;
    }
    /**
     * @return array
     */
    public function toArray()
    {
        return (array) get_object_vars($this);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->userType == AclBuilder::ROLE_ADMIN;
    }


    /**
     * @return bool
     */
    public function isIntranetAdmin()
    {
        return $this->userType == AclBuilder::ROLE_ADMIN || $this->userType == AclBuilder::ROLE_MODERATOR;
    }
}

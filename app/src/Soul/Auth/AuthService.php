<?php
/**
 * File: AuthService.php 
 * @author Stephen Hoogendijk
 *
 */

namespace Soul\Auth;

use Soul\Module;
use Soul\ServiceBase;

class AuthService extends ServiceBase
{

    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = $this->di->get('session');
    }

    /**
     * todo: implement
     */
    public function isLoggedIn()
    {
        return true;
    }
}
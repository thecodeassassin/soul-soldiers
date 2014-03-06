<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Mail 
 */  

namespace Soul;
use Soul\Mail\Provider\ProviderInterface;


/**
 * Class Mail
 *
 * @package Soul
 */
class Mail extends Module
{

    protected $interface;

    /**
     * @param ProviderInterface $provider E-mail provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->interface = $provider;
    }


    
} 
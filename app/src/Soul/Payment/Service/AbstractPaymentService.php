<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AbstractPaymentService 
 */  

namespace Soul\Payment\Service;


 
use Soul\Module;
use Soul\Payment\PaymentServiceInterface;

/**
 * Class AbstractPaymentService
 *
 * @package Soul\Payment\Service
 */
abstract class AbstractPaymentService extends Module implements PaymentServiceInterface
{

}
<?php
namespace Soul\Payment;

use Soul\Model\Payment;

/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
*/

interface PaymentServiceInterface
{

    public function payAmount($amount, $reference);


    public function confirmPayment(Payment $payment);


} 
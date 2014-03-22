<?php
namespace Soul\Payment;

use Soul\Model\Payment;
use Soul\Payment\Data\AbstractData as TransactionData;

/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
*/

interface PaymentServiceInterface
{

    /**
     * @param TransactionData $data
     * @param int|mixed       $userId
     * @param int|mixed       $productId
     *
     * @return mixed
     */
    public function startTransaction(TransactionData $data, $userId, $productId);

    /**
     * @param string $transactionId
     *
     * @return mixed
     */
    public function checkTransaction($transactionId);
} 
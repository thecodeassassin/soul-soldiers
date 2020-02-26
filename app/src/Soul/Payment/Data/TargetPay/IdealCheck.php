<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package IdealStart
 */

namespace Soul\Payment\Data\TargetPay;



use Soul\Payment\Data\AbstractData;
use Soul\Payment\Data\Exception as Exception;

/**
 * Ideal check data
 *
 * Class IdealCheck
 *
 * @package Soul\Payment\Data\TargetPay
 */
class IdealCheck extends AbstractData
{

    /**
     * Check for an existing ideal transaction's status
     *
     * @param int    $layoutCode
     * @param string $transactionId
     *
     * @throws \Soul\Payment\Data\Exception
     *
     */
    public function __construct($layoutCode, $transactionId)
    {
        if (!$layoutCode) {
            throw new Exception('No layout code provided');
        }

        if (!$transactionId) {
            throw new Exception('No transactionId provided');
        }

        $this->rtlo = $layoutCode;
        $this->trxid = $transactionId;
    }

    public function setTestMode($value)
    {
        $this->test = ($value ? 1 : 0);
    }

    /**
     * Layoutcode targetpay
     *
     * @var int
     */
    public $rtlo;

    /**
     * TransactionId
     *
     * @var string
     */
    public $trxid;

    /**
     * Valid only once
     *
     * @var bool
     */
    public $once = true;

    /**
     * Test mode
     *
     * @var integer
     */
    public $test = 0;

}

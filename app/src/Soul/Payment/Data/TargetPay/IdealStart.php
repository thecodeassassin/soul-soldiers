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
 * Ideal start data
 *
 * Class IdealStart
 *
 * @package Soul\Payment\Data\TargetPay
 */
class IdealStart extends AbstractData
{

    /**
     * Build a new ideal transction
     *
     * @param int    $layoutCode
     * @param int    $issuer
     * @param string $description
     * @param float  $amount
     * @param string $returnUrl
     * @param string $reportUrl
     *
     * @throws \Soul\Payment\Data\Exception
     */
    public function __construct($layoutCode, $issuer, $description, $amount, $returnUrl, $reportUrl)
    {
        if (!$layoutCode) {
            throw new Exception('No layout code provided');
        }

        if (!$issuer) {
            throw new Exception('No issuer provided');
        }

        if (!is_numeric($amount)) {
            throw new Exception(sprintf('Invalid amount %s: ', $amount));
        }

        if (!$returnUrl) {
            throw new Exception('No return URL given');
        }

        $this->rtlo = $layoutCode;
        $this->bank = $issuer;
        $this->description = $description;
        $this->amount = $amount;
        $this->returnurl = $returnUrl;
        $this->reporturl = $reportUrl;
        $this->ver = 4;
    }

    public function setTestMode($value)
    {
        $this->test = ($value ? 1 : 0);
    }


    /**
     * Test mode
     *
     * @var integer
     */
    public $test = 0;

    /**
     * Layoutcode targetpay
     *
     * @var int
     */
    public $rtlo;


    /**
     * Issuer code
     *
     * @var int
     */
    public $bank;

    /**
     * Transaction description
     *
     * @var string
     */
    public $description = '';

    /**
     * Currency
     *
     * @var string
     */
    public $currency = 'EUR';

    /**
     * @var float amount in euro
     */
    public $amount;

    /**
     * Language of translaction
     *
     * @var string
     */
    public $language = 'nl';

    /**
     * Return to this URL
     *
     * @var string
     */
    public $returnurl;

    /**
     * Report back to this URL
     *
     * @var string
     */
    public $reporturl = ''; /**
 *
     * API version
     *
     * @var string
     */
    public $ver = 4;


}

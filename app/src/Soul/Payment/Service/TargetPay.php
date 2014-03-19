<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package TargetPay 
 */  

namespace Soul\Payment\Service;


 
use SimpleXMLElement;
use Soul\Model\Payment;

class TargetPay extends AbstractPaymentService
{

    /**
     * @var string
     */
    protected $issuersLink = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';


    /**
     * Returns a list of available banks (issuers)
     *
     * @return array
     */
    public function getIssuers()
    {
        $issuersRaw = file_get_contents($this->issuersLink);
        $issuers = [];

        if ($issuersRaw && $issuerXml = simplexml_load_string($issuersRaw)) {

            for ($count=0; $count < count($issuerXml->children()); $count++) {
                $issuers[] = [
                    'id' => (string) $issuerXml->issuer[$count]['id'],
                    'name' => (string) $issuerXml->issuer[$count]
                ];
            }

        }

        return $issuers;
    }

    /**
     * @param $amount
     * @param string $reference
     *
     * @return \Soul\Model\Payment
     */
    public function payAmount($amount, $reference)
    {

        $payment = new Payment();



        return $payment;
    }


    /**
     * @param Payment $payment
     */
    public function confirmPayment(Payment $payment)
    {

    }
} 
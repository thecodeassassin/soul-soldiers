<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package TargetPay
 */

namespace Soul\Payment\Service;



use SimpleXMLElement;
use Soul\Model\Entry;
use Soul\Model\Event;
use Soul\Model\Payment;
use Soul\Model\User;
use Soul\Payment\Data\AbstractData;
use Soul\Payment\Data\TargetPay\IdealCheck;
use Soul\Payment\Data\TargetPay\IdealStart;

class TargetPay extends AbstractPaymentService
{

    /**
     * @var string
     */
    protected $issuersLink = 'https://transaction.digiwallet.nl/ideal/getissuers?ver=4&format=xml';


    /**
     * Ideal start URL
     *
     * @var string
     */
    public $startUrl = 'https://transaction.digiwallet.nl/ideal/start';



    /**
     * Ideal check URL
     *
     * @var string
     */
    public $checkUrl = 'https://transaction.digiwallet.nl/ideal/check';

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
     * Start ideal transaction
     *
     * @param AbstractData $data
     *
     * @param int $userId
     * @param int $productId
     *
     * @throws \Exception
     * @return mixed|void
     */
    public function startTransaction(AbstractData $data, $userId, $productId)
    {
        if (!$data instanceof IdealStart) {
            throw new \Exception('Not a valid instance of IdealStart passed');
        }

        // get the transaction details
        $resultRaw = file_get_contents($data->toUri($this->startUrl));

        if (!$resultRaw) {
            return false;
        }

        $result = $this->parseIdealStartResult($resultRaw);
        $amount = floatval($data->amount / 100);

        // create a new payment (unconfirmed)
        Payment::createPayment($amount, $result['transactionId'], $userId, $productId);

        return $result;

    }

    /**
     * @param $transactionId
     * @return bool
     */
    public function checkTransaction($transactionId)
    {
        $payment = Payment::findPaymentByTransactionId($transactionId);

        if (!$payment || $payment->confirmed) {
            return false;
        }

        $layoutCode =  $this->config->paymentServices->targetPay->layoutCode;
        $testMode =  (int) $this->config->paymentServices->targetPay->testMode;

        $idealCheck = new IdealCheck($layoutCode, $transactionId);

        // turn test mode on if it's set in the config
        if ($testMode) {
            $idealCheck->test = $testMode;
        }

        $resultRaw = file_get_contents($idealCheck->toUri($this->checkUrl));

        if (!$resultRaw) {
            return false;
        }

        // this result only contains a header
        $result = $this->parseResultHeader($resultRaw);

        if ($result['status'] == 'OK') {

            $user = User::findFirstByUserId($payment->userId);
            $event = Event::findFirstByProductId($payment->productId);
            $entry = Event::findEntryByUserIdAndEventId($payment->userId, $event->eventId);

            $payment->confirmEntryPayment($entry);

            $this->getMail()->sendToUser(
                $user,
                'Bedankt voor je betaling',
                'paymentConfirmed',
                compact('event')
            );


            return true;
        }

        return false;
    }

    /**
     * @param $result
     *
     * @return array
     */
    protected function parseIdealStartResult($result)
    {

        list($resultHeader, $url) = explode('|', $result);

        $header = $this->parseResultHeader($resultHeader);
        $resultCode = $header['code'];
        $transactionId = $header['status'];

        return compact('resultCode', 'transactionId', 'url');
    }

    /**
     * @param $header
     *
     * @throws Exception
     *
     * @return array
     */
    protected function parseResultHeader($header)
    {
        list($code, $status) = explode(' ', $header);

        switch ($code) {
            case 'TP0011':
                throw new Exception('De transactie is geannuleerd');
            break;
            case '000000':
                return compact('code', 'status');
            break;
            default:
                throw new Exception('Er is een fout opgetreden, probeer het later nogmaals.');
        }

    }
}
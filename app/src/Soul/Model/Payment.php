<?php
namespace Soul\Model;

/**
 * Class Payment
 *
 * @package Soul\Model
 */
class Payment extends Base
{

    /**
     *
     * @var integer
     */
    public $paymentId;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var integer
     */
    public $productId;

    /**
     *
     * @var string
     */
    public $transactionId;

    /**
     *
     * @var double
     */
    public $amount;

    /**
     *
     * @var string
     */
    public $confirmed;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblPayment');

        $this->belongsTo('productId', '\Soul\Model\Product', 'productId', ['alias' => 'product']);
    }

    /**
     * @param string|int $transactionId
     *
     * @return Payment
     */
    public static function findPaymentByTransactionId($transactionId)
    {
        return static::findFirstByTransactionId($transactionId);
    }

    /**
     * Confirm this payment
     */
    public function confirmEntryPayment(Entry $entry)
    {
        $this->confirmed = 1;
        $entry->paymentId = $this->paymentId;

        $entry->save();
        $this->save();
    }

    /**
     * Create a payment
     *
     * @param int|float $amount
     * @param string    $reference
     * @param int       $userId
     * @param int       $productId
     * @return bool|Payment
     */
    public static function createPayment($amount, $reference, $userId, $productId)
    {

        // do not create a payment if one already exists with the given reference
        if (static::findPaymentByTransactionId($reference)) {
            return false;
        }

        $payment = new self();

        $payment->amount = $amount;
        $payment->transactionId = $reference;
        $payment->productId = $productId;
        $payment->userId = $userId;
        $payment->confirmed = 0;

        $payment->save();

        return $payment;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'paymentId' => 'paymentId',
            'userId' => 'userId',
            'productId' => 'productId',
            'transactionId' => 'transactionId',
            'amount' => 'amount',
            'confirmed' => 'confirmed'
        );
    }

}

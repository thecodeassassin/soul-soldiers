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

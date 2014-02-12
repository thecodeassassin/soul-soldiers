<?php




class Payment extends \Phalcon\Mvc\Model
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

<?php




class FeedBack extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $feedbackId;
     
    /**
     *
     * @var string
     */
    public $message;
     
    /**
     *
     * @var integer
     */
    public $score;
     
    /**
     *
     * @var integer
     */
    public $userId;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblFeedback');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'feedbackId' => 'feedbackId', 
            'message' => 'message', 
            'score' => 'score', 
            'userId' => 'userId'
        );
    }

}

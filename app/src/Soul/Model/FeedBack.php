<?php
namespace Soul\Model;

/**
 * Class FeedBack
 *
 * @package Soul\Model
 */
class FeedBack extends BaseModel
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

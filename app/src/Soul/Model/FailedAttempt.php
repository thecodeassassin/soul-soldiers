<?php
namespace Soul\Model;

/**
 * Class FailedAttempt
 *
 * @package Soul\Model
 */
class FailedAttempt extends BaseModel
{

    /**
     *
     * @var integer
     */
    public $attemptId;
     
    /**
     *
     * @var string
     */
    public $ipaddress;
     
    /**
     *
     * @var string
     */
    public $attemptinfo;
     
    /**
     *
     * @var integer
     */
    public $count;
     
    /**
     *
     * @var string
     */
    public $timestamp;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblFailedAttempt');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'attemptId' => 'attemptId', 
            'ipaddress' => 'ipaddress', 
            'attemptinfo' => 'attemptinfo', 
            'count' => 'count', 
            'timestamp' => 'timestamp'
        );
    }

}

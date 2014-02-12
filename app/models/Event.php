<?php
/**
 * Class Event
 */
class Event extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $eventId;
     
    /**
     *
     * @var string
     */
    public $name;
     
    /**
     *
     * @var string
     */
    public $details;
     
    /**
     *
     * @var string
     */
    public $systemName;
     
    /**
     *
     * @var string
     */
    public $startDate;
     
    /**
     *
     * @var string
     */
    public $endDate;
     
    /**
     *
     * @var integer
     */
    public $maxEntries;
     
    /**
     *
     * @var integer
     */
    public $productId;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblEvent');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'eventId' => 'eventId', 
            'name' => 'name', 
            'details' => 'details', 
            'systemName' => 'systemName', 
            'startDate' => 'startDate', 
            'endDate' => 'endDate', 
            'maxEntries' => 'maxEntries', 
            'productId' => 'productId'
        );
    }

}

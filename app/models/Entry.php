<?php
/**
 * Class Entry
 */
class Entry extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $entryId;
     
    /**
     *
     * @var integer
     */
    public $eventId;
     
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
		$this->setSource('tblEntry');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'entryId' => 'entryId', 
            'eventId' => 'eventId', 
            'userId' => 'userId'
        );
    }

}

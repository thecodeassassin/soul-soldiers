<?php
namespace Soul\Model;

/**
 * Class Entry
 *
 * @package Soul\Model
 */
class Entry extends BaseModel
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

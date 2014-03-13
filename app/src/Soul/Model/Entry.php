<?php
namespace Soul\Model;

/**
 * Class Entry
 *
 * @package Soul\Model
 */
class Entry extends Base
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
     * @var integer
     */
    public $paymentId;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblEntry');

        $this->hasOne('userId', '\Soul\Model\User', 'userId', ['alias' => 'user']);
        $this->belongsTo('eventId', '\Soul\Model\Event', 'eventId', ['alias' => 'event']);
        $this->hasOne('paymentId', '\Soul\Model\Payment', 'paymentId', ['alias' => 'payment']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'entryId' => 'entryId',
            'eventId' => 'eventId',
            'userId' => 'userId',
            'paymentId' => 'paymentId'
        );
    }

}

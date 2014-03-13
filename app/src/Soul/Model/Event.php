<?php
namespace Soul\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model\Query;

/**
 * Class Event
 *
 * @package Soul\Model
 */
class Event extends Base
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

        $this->hasOne('productId', '\Soul\Model\Product', 'productId', ['alias' => 'product']);
        $this->hasMany('eventId', '\Soul\Model\Entry', 'eventId', ['alias' => 'entries']);

    }

    /**
     * Return the current event
     *
     * @return \Phalcon\Mvc\Model
     */
    public static function getCurrent()
    {
        $event =  self::findFirst(['order' => 'abs(now() - startDate) asc']);
        return $event;
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

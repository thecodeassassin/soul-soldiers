<?php

namespace Soul\Model;

/**
 * Class SeatMap
 *
 * @package Soul\Model
 */
class SeatMap extends Base
{

    /**
     *
     * @var integer
     */
    public $seatMapId;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var integer
     */
    public $xCount;

    /**
     *
     * @var integer
     */
    public $yCount;

    /**
     * @var integer
     */
    public $tableLimit;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblSeatMap');

        $this->belongsTo('seatMapId', '\Soul\Model\Event', 'seatMapId');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'seatMapId' => 'seatMapId',
            'description' => 'description',
            'image' => 'image',
            'xCount' => 'xCount',
            'yCount' => 'yCount',
            'tableLimit' => 'tableLimit',
            'posX' => 'posX',
            'posY' => 'posY'
        );
    }

}

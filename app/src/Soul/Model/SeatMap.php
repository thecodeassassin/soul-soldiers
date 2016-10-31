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
     * @var string 
    */
    public $cssClass;
    
    /**
     * 
     * @var string 
    */
    public $map;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblSeatMap');

        $this->belongsTo('seatMapId', '\Soul\Model\Event', 'seatMapId');
    }
    
    public function getMap() 
    {
        return json_decode($this->map);
    }
    
    public function getParsedMap($flat = false)
    {
        $map = $this->getMap();
        $parsedMap = [];
        $blockSizePx = 0;
        
        if (is_array($map)) {
            foreach ($map as $idx => $blocks) {
                $largestBlockSize = count($blocks[0]) * 35;
                $sNum = 1;
                if ($blockSizePx < $largestBlockSize) $blockSizePx = $largestBlockSize;
                
                foreach ($blocks as $block) {
                    foreach ($block as $seat) {
                        if ($seat == 's') {
                            $seatName = $idx+1 . ".$sNum";
                            $sNum += 1;
                            if ($flat) $parsedMap[] = $seatName;
                        } else {
                            $seatName = "";
                        }
                        if (!$flat) {
                            $parsedMap[$idx+1][] = $seatName;
                        }
                    }
                }
            }
        } else {
            throw new \Exception('Cannot read seatmap!, invalid JSON');
        }
        
        return ["blockSizePx" => $blockSizePx, 'map' => $parsedMap];
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
            'posX' => 'posX',
            'posY' => 'posY',
            'map' => 'map',
            'blockedSeats' => 'blockedSeats',
            'cssClass' => 'cssClass'

        );
    }

}

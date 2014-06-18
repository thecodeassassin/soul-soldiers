<?php
namespace Soul\Model;

/**
 * Class TournamentScore
 *
 * @package Soul\Model
 */
class TournamentScore extends Base
{

    /**
     *
     * @var integer
     */
    public $tournamentUserId;
     
    /**
     *
     * @var integer
     */
    public $score;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournamentScore');

    }

    public function getSource()
    {
        return 'tblTournamentScore';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tournamentUserId' => 'tournamentUserId', 
            'score' => 'score'
        );
    }

}

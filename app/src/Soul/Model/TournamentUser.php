<?php
namespace Soul\Model;


class TournamentUser extends Base
{

    /**
     *
     * @var integer
     */
    public $tournamentId;
     
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
		$this->setSource('tblTournamentUser');

        $this->belongsTo('tournamentId', '\Soul\Model\Tournament', 'tournamentId', ['alias' => 'tournament']);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tournamentId' => 'tournamentId', 
            'userId' => 'userId'
        );
    }

}

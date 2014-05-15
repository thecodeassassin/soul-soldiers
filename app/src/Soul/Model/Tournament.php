<?php
namespace Soul\Model;

/**
 * Class Tournament
 */
class Tournament extends Base
{

    /**
     *
     * @var integer
     */
    public $tournamentId;
     
    /**
     *
     * @var string
     */
    public $name;
     
    /**
     *
     * @var integer
     */
    public $type;
     
    /**
     *
     * @var string
     */
    public $startDate;
     
    /**
     *
     * @var string
     */
    public $challongeId;
     
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournament');

        $this->hasMany('tournamentId', '\Soul\Model\TournamentUser', 'tournamentId', ['alias' => 'players']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tournamentId' => 'tournamentId', 
            'name' => 'name', 
            'type' => 'type', 
            'startDate' => 'startDate', 
            'challongeId' => 'challongeId'
        );
    }

}

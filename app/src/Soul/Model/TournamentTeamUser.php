<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

/**
 * Class tblTournamentTeamUser
 */
class TournamentTeamUser extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $teamId;

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
		$this->setSource('tblTournamentTeamUser');

        $this->hasOne('teamId', '\Soul\Model\TournamentTeam', 'teamId', ['alias' => 'team']);
        $this->hasOne('userId', '\Soul\Model\Entry', 'userId', ['alias' => 'user']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'teamId' => 'teamId',
            'userId' => 'userId'
        );
    }

}

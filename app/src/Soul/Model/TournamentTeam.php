<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Model;
/**
 * Class tblTournamentTeam
 */
class TournamentTeam extends Base
{

    /**
     *
     * @var integer
     */
    public $teamId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $tournamentId;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournamentTeam');
        $this->belongsTo('tournamentId', '\Soul\Model\Tournament', 'tournamentId', ['alias' => 'tournament']);
    }

    /**
     * @return mixed
     */
    public function getPlayers()
    {
        return TournamentUser::findByTeamId($this->teamId);
    }

    /**
     * Before deleting, remove all users from the teams
     */
    public function beforeDelete()
    {
        $players = $this->getPlayers();

        // remove the team
        foreach ($players as $player) {
            $player->teamId = null;
            $player->save();
        }
    }

    /**
     * @param $tournamentId
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findByTournamentId($tournamentId)
    {
        return self::find('tournamentId = \''.$tournamentId.'\'');
    }

    /**
     * @param $teamId
     *
     * @return TournamentTeam
     */
    public static function findFirstById($teamId)
    {
        return self::find('teamId = \''.$teamId.'\'');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'teamId' => 'teamId',
            'name' => 'name',
            'tournamentId' => 'tournamentId'
        );
    }

}

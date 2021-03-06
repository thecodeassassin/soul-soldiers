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
     * @var integer
     */
    public $seed;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournamentTeam');

        $this->hasOne('tournamentId', '\Soul\Model\Tournament', 'tournamentId', ['alias' => 'tournament']);
    }

    /**
     * @return mixed
     */
    public function getPlayers()
    {
        return TournamentUser::findByTeamId($this->teamId);
    }


    private function getPlayerIdentifiers()
    {
        $players = $this->getPlayers();
        $playerIds = [];

        foreach ($players as $player) {
            $playerIds[] = $player->userId;
        }

        return $playerIds;

    }

    public function userInTeam($userId)
    {
        $players = $this->getPlayerIdentifiers();

        return in_array($userId, $players);

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
     * Returns a read-only copy of tournament
     *
     * @return Tournament
     */
    public function getTournament()
    {
        return Tournament::findFirstById($this->tournamentId);
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
        return self::findFirst('teamId = \''.$teamId.'\'');
    }

    /**
     * @param $tournamentId
     *
     * @return bool
     */
    public static function deleteAllByTournamentId($tournamentId)
    {
        $existingTeams = self::findByTournamentId($tournamentId);
        foreach ($existingTeams as $existingTeam) {
            $existingTeam->delete();
        }

        return true;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'teamId' => 'teamId',
            'name' => 'name',
            'tournamentId' => 'tournamentId',
            'seed' => 'seed'
        );
    }

}

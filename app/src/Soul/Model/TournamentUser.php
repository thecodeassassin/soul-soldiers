<?php
namespace Soul\Model;


use Phalcon\Mvc\Model\Message;

class TournamentUser extends Base
{

    /**
     * @var integer
     */
    public $tournamentUserId;

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
     * @var bool
     */
    public $active;

    /**
     * @var integer
     */
    public $teamId;
    /**
     * @var integer
     */
    public $participantId;

    /**
     * @var integer
     */
    public $rank;

    /**
     * @var integer
     */
    public $seed;

    /**
     * @var bool Skips deletion checks, use with care
     */
    public $deleteForce = false;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournamentUser');

        $this->belongsTo('tournamentId', '\Soul\Model\Tournament', 'tournamentId', ['alias' => 'tournament']);

        $this->hasOne('userId', '\Soul\Model\User', 'userId', ['alias' => 'user']);

    }

    public function beforeCreate()
    {

    }

    public function afterFetch()
    {

    }

    /**
     *
     */
    public function beforeDelete()
    {
        return true;
    }

    /**
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param $userId
     * @return TournamentUser
     */
    public static function findFirstByTournamentUserId($userId)
    {
        return self::findFirst('tournamentUserId = \''.$userId.'\'');
    }

    /**
     * @param $tournamentId
     * @param $userId
     *
     * @return TournamentUser
     */
    public static function findFirstByTournamentIdAndUserId($tournamentId, $userId)
    {
        return self::findFirst('tournamentId = \''.$tournamentId.'\' AND userId = \''.$userId.'\'');
    }


    /**
     * @param $userId
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findByUserId($userId)
    {
        return self::find('userId = \''.$userId.'\'');
    }

    /**
     * @param $teamId
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findByTeamId($teamId)
    {
        return self::find(['teamId = \''.$teamId.'\'', 'order' => 'rank ASC']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tournamentUserId' => 'tournamentUserId',
            'tournamentId' => 'tournamentId',
            'userId' => 'userId',
            'active' => 'active',
            'teamId' => 'teamId',
            'participantId' => 'participantId',
            'rank' => 'rank',
            'seed' => 'seed'
        );
    }

}

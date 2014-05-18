<?php
namespace Soul\Model;


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
     * @var int
     */
    public $totalScore;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblTournamentUser');

        $this->belongsTo('tournamentId', '\Soul\Model\Tournament', 'tournamentId', ['alias' => 'tournament']);

        $this->hasOne('userId', '\Soul\Model\User', 'userId', ['alias' => 'user']);
        $this->hasMany('tournamentUserId', '\Soul\Model\TournamentScore', 'tournamentUserId', ['alias' => 'scores']);

    }

    public function afterFetch()
    {
        $this->totalScore = $this->getTotalScore();
    }

    /**
     * @return int
     */
    public function getTotalScore()
    {
        $totalScore = 0;

        if ($this->scores) {
            foreach ($this->scores as $score) {
                $totalScore += (int)$score->score;
            }
        }

        return $totalScore;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'tournamentUserId' => 'tournamentUserId',
            'tournamentId' => 'tournamentId',
            'userId' => 'userId',
            'active' => 'active'
        );
    }

}

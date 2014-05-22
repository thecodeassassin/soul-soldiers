<?php
namespace Soul\Model;

use Soul\Tournaments\Challonge;
use Soul\Tournaments\Challonge\Tournament as ChallongeTournament;

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
     * @var string
     */
    public $startDateString;

    /**
     *
     * @var string
     */
    public $challongeId;


    /**
     * @var string
     */
    public $systemName;

    /**
     * @var string
     */
    public $typeString;

    /**
     * @var array
     */
    public $playersArray = [];

    /**
     * @var array
     */
    public $entries = [];

    /**
     * @var array
     */
    public $matches = [];

    /**
     * @var bool
     */
    public $isChallonge = false;

    const TYPE_TOP_SCORE = 1;
    const TYPE_SINGLE_ELIMINATION = 2;
    const TYPE_DOUBLE_ELIMINATION = 3;

    /**
     * @var ChallongeTournament
     */
    public $challonge;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblTournament');
        $this->hasMany('tournamentId', '\Soul\Model\TournamentUser', 'tournamentId', ['alias' => 'players']);
    }

    /**
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function getLatestTournaments()
    {
        return self::find('startDate < \''.date('Y-m-d H:i:s', strtotime('+1 week')) . '\'');
    }

    public function afterFetch()
    {
        $types = [
            self::TYPE_TOP_SCORE => 'Top score',
            self::TYPE_SINGLE_ELIMINATION => 'Single elimination',
            self::TYPE_DOUBLE_ELIMINATION => 'Double elimination'
        ];

        if ($this->challongeId) {

            $this->challonge = new ChallongeTournament($this->challongeId);
            $this->isChallonge = true;

        }

        $this->typeString = $types[$this->type];
        $this->startDateString = date('d-m-y H:i', strtotime($this->startDate));

        if (!$this->isChallonge) {
            $this->playersArray = $this->players->toArray();


            array_walk($this->playersArray, function(&$player) {
                    $player['user'] = User::findFirstByUserId($player['userId'])->toArray();

                    $scoreResult = $this->players->filter(function($obj) use ($player){
                            if ($obj->userId == $player['userId']) {
                                return $obj;
                            }
                            return null;
                        });

                    if (count($scoreResult) == 1) {
                        $player['totalScore'] = $scoreResult[0]->totalScore;
                    } else {
                        $player['totalScore'] = 0;
                    }

                });

            if ($this->type == self::TYPE_TOP_SCORE) {

                usort($this->playersArray, function ($left, $right) {

                        if ($left['totalScore'] == $right['totalScore']) {
                            return 0;
                        }

                        return ($left['totalScore'] > $right['totalScore'] ? -1 : 1);
                    });

            }
        } else {
            $players = $this->challonge->getPlayers();

            if ($players) {
                foreach ($players->participant as $player) {

                    $name = (string)$player->name;

                    $playerData = [
                        'user' => ['nickName' => $name],
                        'active' => $player->active
                    ];

                    if (is_numeric($player->{'final-rank'})) {
                        $playerData['rank'] = $player->{'final-rank'};
                    } else {
                        $playerData['rank'] = null;
                    }

                    $this->playersArray[] = $playerData;
                    $this->entries[] = $name;

                }

            }

            // generate an image for this tournament
            if ($image =  $this->challonge->getOverviewImage()) {
                $tmpFile = $this->getConfig()->application->cacheDir . $this->systemName . '.png';
                file_put_contents($tmpFile, file_get_contents($image));


                $original = new \Phalcon\Image\Adapter\GD($tmpFile);
                $original->crop($original->getWidth(), $original->getHeight(), 0, 100);
                $original->save(APPLICATION_PATH . '/../public/img/tournaments/'.$this->systemName.'.png');
            }

        }

    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        // a top score tournament ends when there is only 1 contestent left
        if ($this->type == self::TYPE_TOP_SCORE) {

            $players = TournamentUser::query()
                ->where("tournamentId = :id:")
                ->andWhere("active = 1")
                ->bind(array("id" => $this->tournamentId))
                ->execute();

            return count($this->playersArray) > 1 && count($players) == 1;

        } elseif ($this->isChallonge) {
            return $this->challonge->isCompleted();
        }


    }

    /**
     * @param $userId
     * @return bool
     */
    public function hasEntered($userId)
    {
        if (!$this->challonge && !empty($this->players)) {

            foreach ($this->players as $player) {
                if ($player->userId == $userId) {
                    return true;
                }
            }
        }


        if ($this->isChallonge) {
            $user = User::findFirstByUserId($userId);

            if ($user) {
                return in_array($user->nickName, $this->entries);
            }
        }

        return false;
    }

    /**
     * @param $systemName
     * @return Tournament
     */
    public static function findFirstBySystemName($systemName)
    {
        return self::findFirst('systemName = \''.$systemName.'\'');
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
            'challongeId' => 'challongeId',
            'systemName' => 'systemName'

        );
    }

}

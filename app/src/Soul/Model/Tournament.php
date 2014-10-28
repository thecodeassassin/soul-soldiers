<?php
namespace Soul\Model;

use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Soul\Tournaments\Challonge;
use Soul\Tournaments\Challonge\Exception as ChallongeException;
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
    public $rules;

    /**
     * @var string
     */
    public $prizes;

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

    /**
     * @var bool
     */
    public $hasError = false;

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

    public function validation()
    {
        $existing = self::findFirst(["tournamentId='$this->tournamentId'"]);

        if ($existing) {
            if ($existing->name != $this->name) {
                $this->validate(new Uniqueness(
                        array(
                            "field"   => "name",
                            "message" => "Er bestaat al een toernooi met deze naam"
                        )
                    ));
            }
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize the system name before saving the tournament
     */
    public function beforeCreate()
    {
        $this->systemName = preg_replace('/[^A-Za-z0-9\_]/', '', strtolower(str_replace(' ', '_', $this->name)));

        $challongeTypes = [
          self::TYPE_SINGLE_ELIMINATION => 'single elimination',
          self::TYPE_DOUBLE_ELIMINATION => 'double elimination',
        ];

        // if the tournament is single or double elimination, register the tournament in challonge
        if (in_array($this->type, [self::TYPE_SINGLE_ELIMINATION, self::TYPE_DOUBLE_ELIMINATION])) {
            $challongeApi = $this->getChallongeAPI();
            $challongeApi->createTournament([
              'tournament[name]' => $this->name,
              'tournament[tournament_type]' => $challongeTypes[$this->type],
              'tournament[url]' => $this->systemName,
              'tournament[subdomain]' => $challongeApi->getSubDomain(),
              'tournament[open_signup]' => 'false',
              'tournament[start_at]' => $this->startDate
            ]);
            $this->challongeId = $this->systemName;
        }
    }

    /**
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function getLatestTournaments()
    {
        return self::find('startDate < \''.date('Y-m-d H:i:s', strtotime('+1 week')) . '\'');
    }

    /**
     * Returns a list of tournament types
     *
     * @return array list of types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_TOP_SCORE => 'Top score',
            self::TYPE_SINGLE_ELIMINATION => 'Single elimination',
            self::TYPE_DOUBLE_ELIMINATION => 'Double elimination'
        ];
    }

    /**
     *
     */
    public function afterFetch()
    {
        $types = self::getTypes();

        if ($this->challongeId) {

            $this->isChallonge = true;
            try {

                $this->challonge = new ChallongeTournament($this->challongeId);

            } catch(ChallongeException $e) {
                $this->hasError = true;
                $this->challonge = null;
            }

        }

        $this->typeString = $types[$this->type];
        $this->startDateString = date('d-m-y H:i', strtotime($this->startDate));


        // todo fix this logic
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

            if (!$this->hasError) {
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

                // always remove the old image
                $newImage = $this->getConfig()->application->cacheDir . $this->systemName . '.png';


                if (file_exists($newImage)) {
                    unlink($newImage);
                }

                // generate an image for this tournament
                if ($image =  $this->challonge->getOverviewImage()) {
                    $tmpFile = $this->getConfig()->application->cacheDir . $this->systemName . '.png';
                    file_put_contents($tmpFile, file_get_contents((string)$image));


                    $original = new \Phalcon\Image\Adapter\GD($tmpFile);
                    $original->crop($original->getWidth(), $original->getHeight(), 0, 100);
                    $original->save($newImage);
                    chmod($newImage, 0777);
                }

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
            'systemName' => 'systemName',
            'rules' => 'rules',
            'prizes' => 'prizes',

        );
    }

    /**
     * @return Challonge
     */
    protected function getChallongeAPI()
    {
        return $this->getDI()->get('challonge');
    }

}

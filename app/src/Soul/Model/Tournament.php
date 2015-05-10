<?php
namespace Soul\Model;

use Phalcon\Forms\Element\Date;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Soul\Tournaments\Challonge;
use Soul\Tournaments\Challonge\Exception as ChallongeException;
use Soul\Tournaments\Challonge\Tournament as ChallongeTournament;
use Soul\Util;

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
     * @var integer
     */
    public $isTeamTournament;

    /**
     * @var
     */
    public $state;

    /**
     * @var integer
     */
    public $teamSize;

    /**
     * @var string
     */
    public $typeString;

    /**
     * @var string
     */
    public $stateString;

    /**
     * @var string image base64
     */
    public $image;

    /**
     * @var string
     */
    public $data;

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


    /**
     * True when only updating state
     *
     * @var bool
     */
    public $onlyStateUpdate = false;

    const TYPE_TOP_SCORE = 1;
    const TYPE_SINGLE_ELIMINATION = 2;
    const TYPE_DOUBLE_ELIMINATION = 3;

    const STATE_PENDING = 0;
    const STATE_STARTED = 1;
    const STATE_FINISHED = 2;

    const BYE = 'FREE PASS';

    /**
     * @var ChallongeTournament
     */
    public $challonge;

    public $challongeTypes = [
        self::TYPE_SINGLE_ELIMINATION => 'single elimination',
        self::TYPE_DOUBLE_ELIMINATION => 'double elimination',
    ];

    /**
     * @var
     */
    public $playerCacheKey;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblTournament');
        $this->hasMany('tournamentId', '\Soul\Model\TournamentTeam', 'tournamentId', ['alias' => 'teams']);
        $this->hasMany('tournamentId', '\Soul\Model\TournamentUser', 'tournamentId', ['alias' => 'players', 'order' => 'rank']);
        $this->hasMany('tournamentId', '\Soul\Model\TournamentScore', 'tournamentId', ['alias' => 'scores']);

    }

    /**
     * @return Simple
     */
    public function getTournamentPlayers()
    {
        return $this->getPlayers(['order' => 'rank ASC']);
    }

    public function validation()
    {
        $existing = self::findFirstBySystemName($this->systemName);

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

        if ($this->type == self::TYPE_TOP_SCORE && $this->isTeamTournament()) {
            $this->appendMessage(new Message('Teams worden alleen ondersteund in single of double elimination toernooien.'));
            return false;
        }

        if ($this->isTeamTournament() && !is_numeric($this->teamSize)) {
            $this->validate(new PresenceOf([
                "field"   => "teamSize",
                "message" => "Je dient een team grootte op te geven voor team toernooien"
            ]));
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * When creating
     */
    public function beforeValidationOnCreate()
    {
        $this->systemName = preg_replace('/[^A-Za-z0-9\_]/', '', strtolower(str_replace(' ', '_', $this->name)));

        if (!$this->isTeamTournament) {
            $this->setTeamTournament(false);
        }

        $this->state = self::STATE_PENDING;

    }

    /**
     *
     */
    public function beforeValidationOnUpdate()
    {
        $existing = self::findFirstBySystemName($this->systemName);

        if (!$this->isTeamTournament && $existing->isTeamTournament()) {
            $this->setTeamTournament(false);

            $this->teamSize = null;

            // remove any teams associated with this tournament
            $tournamentTeams = TournamentTeam::findByTournamentId($this->tournamentId);

            foreach ($tournamentTeams as $team) {
                $players = $team->getPlayers();

                // remove users from the team
                foreach ($players as $player) {
                    $player->teamId = null;
                    $player->save();
                }

                // delete the tournament
                $team->delete();
            }

        }

    }

    /**
     * Before deleting a tournament
     *
     * @return bool
     */
    public function beforeDelete()
    {

        // delete all players associated with this tournament
        $this->deletePlayers();

        return true;
    }

    public function beforeUpdate()
    {

        if (!$this->onlyStateUpdate) {

            $this->deletePlayers();
            $this->reset();

        }

        return true;
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

    public static function getStates()
    {
        return [
            self::STATE_PENDING => 'Registratie open',
            self:: STATE_STARTED => 'Gestart, registratie gesloten',
            self::STATE_FINISHED => 'Afgelopen'
        ];
    }

    /**
     *
     */
    public function afterFetch()
    {

        $this->playerCacheKey = sprintf('tournament_%s_playersarray', $this->systemName);

        $types = self::getTypes();
        $states = self::getStates();

        $this->typeString = $types[$this->type];
        $this->stateString = $states[$this->state];


        $this->startDateString = date('d-m-y H:i', strtotime($this->startDate));

    }


    /**
     * @param $userId
     * @return bool
     */
    public function hasEntered($userId)
    {
        if (!empty($this->players)) {

            foreach ($this->players as $player) {
                if ($player->userId == $userId) {
                    return true;
                }
            }
        }

        return false;
    }

    public function generatePlayers($count)
    {
        $users = User::find()->toArray();
        $userCount = 0;

        shuffle($users);

        foreach ($users as $user) {

            if ($userCount == $count) {
                break;
            }

            if (!$this->hasEntered($user['userId'])) {
                $this->registerPlayer($user['userId']);
                $userCount += 1;
            }
        }

    }

    /**
     * @param $userId
     */
    public function registerPlayer($userId)
    {
        $tournamentUser = new TournamentUser();
        $tournamentUser->userId = $userId;
        $tournamentUser->tournamentId = $this->tournamentId;


        $tournamentUser->active = 1;
        $tournamentUser->save();

    }

    /**
     * @param array $users
     */
    public function updateRanks(array $users)
    {
        $players = $this->players;
        $playersArray = [];

        foreach ($players as $player) {
            $playersArray[$player->userId] = $player;
        }

        $rank = 1;
        foreach ($users as $userId) {
            /** @var User $user */
            $user = $playersArray[$userId];


            // save the new rank
            $user->rank = $rank;
            $user->save();

            $rank++;
        }
    }

    /**
     *
     */
    public function start()
    {
        $this->state = Tournament::STATE_STARTED;
        $this->onlyStateUpdate = true;
        $this->save();
    }

    /**
     * @param bool $teamTournament
     * @param bool $manualData
     */
    public function updateBracketData($teamTournament = false, $manualData = false)
    {

        if (!$this->isEliminationTournament()) {
            $manualData = '[]';
        }

        if ($manualData !== false) {
            $this->data = $manualData;
        } else {
            $results = [];
            $tournamentTeams = [];

            if ($teamTournament) {
                $teams = $this->teams;
                $tournamentTeams = [];

                foreach ($teams as $team) {

                    /** @var TournamentTeam $team */
                    $tournamentTeams[] = $team->name;
                }


            } else {
                $tournamentPlayers = $this->getTournamentPlayers();
                foreach ($tournamentPlayers as $player) {

                    /** @var TournamentUser $player */
                    $tournamentTeams[] = $player->user->nickName;
                }
            }

            // generate byes
            $playerCount = count($tournamentTeams);
            $nextPowerOfTwo = Util::nextPowerOfTwo($playerCount);
            $tempTeams = [];

            $tournamentTeams = $this->sortBySeed($tournamentTeams);
            if ($playerCount < $nextPowerOfTwo) {
                for($i=0;$i< $nextPowerOfTwo - $playerCount;$i++) {
                    $player = array_pop($tournamentTeams);

                    $tempTeams[] = $player;
                    $tempTeams[] = self::BYE;

                }
            }
            $tournamentTeams = array_merge($tournamentTeams, $tempTeams);

            // make the matches
            $matches = array_chunk($tournamentTeams, 2);

            // create the result array
            $resultCount = count($matches);
            while($resultCount % 2 == 0) {

                $matchResults = [];

                for ($i=0;$i<$resultCount;$i++) {
                    $matchResults[] = [null,null];
                }

                $results[] = $matchResults;
                $resultCount = $resultCount / 2;
            }

            // set auto bye wins
            foreach ($results[0] as $roundNum => &$round) {
                $match = $matches[$roundNum];
                $player1 = $match[0];
                $player2 = $match[1];

                if ($player1 == self::BYE) {
                    $round[0] = 0;
                    $round[1] = 1;
                } else if ($player2 == self::BYE) {
                    $round[0] = 1;
                    $round[1] = 0;
                }
            }



            $data = array(
                'teams' => $matches,
                'results' => $results
            );

            $this->data = json_encode($data);
        }

        $this->onlyStateUpdate = true;
        $this->save();

    }

    /**
     * @return bool
     */
    public function isEliminationTournament()
    {
        return (in_array($this->type, array(self::TYPE_DOUBLE_ELIMINATION, self::TYPE_SINGLE_ELIMINATION)));
    }

    public function generateTeams()
    {
        $teams = [];
        $players = [];

        // delete all existing teams first
        TournamentTeam::deleteAllByTournamentId($this->tournamentId);

        $tournamentPlayers = $this->getTournamentPlayers();
        foreach ($tournamentPlayers as $player) {
            $players[] = $player->userId;
        }



        while (count($players) > 0) {

            $newPlayers = array_splice($players, $this->teamSize);
            $teams[] = $players;

            $players = $newPlayers;
        }

        $num = 1;
        foreach ($teams as $team) {
            $tournamentTeam = new TournamentTeam();
//                $teamNames = [];
//
//                foreach ($team as $userId) {
//                    $user = User::findFirstByUserId($userId);
//                    $teamNames[] = $user->nickName;
//                }

            $tournamentTeam->name = 'Team '.$num;
            $tournamentTeam->tournamentId = $this->tournamentId;
            $tournamentTeam->save();

            // add all the players to the team
            foreach ($team as $teamPlayer) {
                $tournamentUser = TournamentUser::findFirstByTournamentIdAndUserId($this->tournamentId, $teamPlayer);
                $tournamentUser->teamId = $tournamentTeam->teamId;
                $tournamentUser->save();
            }

            $num += 1;
        }

        return $teams;
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
     * @param $tournamentId
     * @return Tournament
     */
    public static function findFirstById($tournamentId)
    {
        return self::findFirst('tournamentId = \''.$tournamentId.'\'');
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
            'systemName' => 'systemName',
            'rules' => 'rules',
            'prizes' => 'prizes',
            'isTeamTournament' => 'isTeamTournament',
            'teamSize' => 'teamSize',
            'state' => 'state',
            'image' => 'image',
            'data' => 'data'
        );
    }

    /**
     * @param $val
     */
    public function setTeamTournament($val)
    {
        $this->isTeamTournament = ($val ? 1 : 0);
    }

    public function isTeamTournament()
    {
        return (bool)$this->isTeamTournament;
    }

    /**
     * @return null|ChallongeTournament
     */
    public function getChallongeTournament()
    {
        try {
            return new ChallongeTournament($this->challongeId);

        } catch(ChallongeException $e) {
            $this->hasError = true;
            $challonge = null;
        }

        return null;
    }

    /**
     * Deletes all players associated with this tournament
     */
    public function deletePlayers()
    {

        // delete all the teams associated with this tournament
        if ($this->isTeamTournament()) {
            foreach ($this->teams as $team) {
                $team->delete();
            }
        }

        // then delete all the players themselves
        if (count($this->players) > 0) {

            foreach ($this->players as $player) {

                // force delete this player, since we are cleaning up the tournament
                $player->deleteForce = true;
                $player->delete();
            }
        }

        $this->updateBracketData(false, null);
    }

    /**
     *
     */
    public function reset()
    {
        $this->updateBracketData(false, null);

        $this->state = Tournament::STATE_PENDING;
        $this->onlyStateUpdate = true;
        $this->save();
    }

    /**
     * @return string
     */
    protected function getProperStartDate()
    {
        $startDate = new \DateTime($this->startDate);
        $startDate->setTimezone(new \DateTimeZone('Europe/Amsterdam'));

        return (string)$startDate->format('Y-m-d H:i:s O');
    }

    /**
     * Checks whether or not this tournament is linked on challonge
     * @return bool
     */
    public function isChallongeTournament()
    {
        return in_array($this->type, [self::TYPE_SINGLE_ELIMINATION, self::TYPE_DOUBLE_ELIMINATION]);
    }

    /**
     * @return Challonge
     */
    protected function getChallongeAPI()
    {
        return $this->getDI()->get('challonge');
    }

    /**
     * @param Challonge $challonge
     */
    protected function appendChallongeErrors(Challonge $challonge)
    {
        foreach ($challonge->errors as $error) {
            $error = (string) $error;
            if (!empty($error)) {
                $this->appendMessage(new Message($error));
            }
        }
    }

    /**
     * http://stackoverflow.com/questions/8355264/tournament-bracket-placement-algorithm
     * Author: DarkAngel
     *
     * Simply ensures that the top player (first player) is seeded/ ordered against the worst player.
     *  1 vs 8, 4 vs 5, 2 vs 7, 3 vs 6 <-- Typical seeding outcome from this sorting.
     *
     * @param $players
     *
     * @return array
     */
    protected  function sortBySeed($players)
    {

        $count = count($players);

        for ($i = 0; $i < log($count / 2, 2); $i++) {
            $out = array();

            foreach ($players as $player) {
                $splice = pow(2, $i);

                $out = array_merge($out, array_splice($players, 0, $splice));

                $out = array_merge($out, array_splice($players, -$splice));
            }

            $players = $out;
        }
//
        return $players;

    }

}

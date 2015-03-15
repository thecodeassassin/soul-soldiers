<?php
namespace Soul\Model;

use Phalcon\Forms\Element\Date;
use Phalcon\Mvc\Model\Message;
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
     * Sanitize the system name before saving the tournament
     */
    public function beforeCreate()
    {

        $this->systemName = preg_replace('/[^A-Za-z0-9\_]/', '', strtolower(str_replace(' ', '_', $this->name)));

    }

    /**
     * When creating
     */
    public function beforeValidationOnCreate()
    {

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

        // clear the menu cache
        self::clearCache($this);

        // delete all players associated with this tournament
        $this->deletePlayers();

        return true;
    }

    public function beforeUpdate()
    {

        if (!$this->onlyStateUpdate) {

            // always delete player cache when updating a tournament
            self::clearCache($this);

            $this->deletePlayers();

        }

        return true;
    }

    /**
     * before save event
     */
    public function beforeSave()
    {
        // remove the tournament menu caching before saving
        self::clearCache($this);
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

//        die(var_dump($this->players[0]->user));

    }

    /**
     * @deprecated
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
            return $this->getChallongeTournament->isCompleted();
        }

        return false;
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

        self::clearCache($this);

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
     * @param Tournament $tournament
     *
     * @return bool
     */
    public static function clearCache(Tournament $tournament)
    {
        $cache = $tournament->getCache();
        $return = false;

        if ($cache->exists($tournament->playerCacheKey)) {
            $return = $cache->delete($tournament->playerCacheKey);
        }

        if ($cache->exists('tournament_menu')) {
            $return = $cache->delete('tournament_menu');
        }

        return $return;

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
            'isTeamTournament' => 'isTeamTournament',
            'teamSize' => 'teamSize',
            'state' => 'state',
            'image' => 'image'
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
    protected function deletePlayers()
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

}

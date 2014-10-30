<?php
namespace Soul\Model;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblTournament');
        $this->hasMany('tournamentId', '\Soul\Model\TournamentTeam', 'tournamentId', ['alias' => 'teams']);
        $this->hasMany('tournamentId', '\Soul\Model\TournamentUser', 'tournamentId', ['alias' => 'players']);

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

        if ($this->isChallongeTournament()) {
            return $this->createChallongeTournament();
        }
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
        // when deleting a tournament, also delete the linked challonge
        if ($this->isChallongeTournament()) {
           return $this->deleteChallongeTournament();
        }

        // delete all players associated with this tournament
        if (count($this->players) > 0) {
            foreach ($this->players as $player) {
                $player->delete();
            }
        }

        return true;
    }

    public function beforeUpdate()
    {

        $databaseEntry = self::findFirstBySystemName($this->systemName);

        // if the tournament changed to non challonge, delete the challonge tournament
        if ($databaseEntry->isChallongeTournament() && !$this->isChallongeTournament()) {
            if (!$this->deleteChallongeTournament()) {
                return false;
            }

        } elseif (!$databaseEntry->isChallongeTournament() && $this->isChallongeTournament()) {
            if (!$this->createChallongeTournament()) {
                return false;
            }
        }

        // when updating a tournament, also update the linked challonge
        if ($this->isChallongeTournament()) {
            $challongeApi = $this->getChallongeAPI();
            $editAction = $challongeApi->updateTournament($this->challongeId, [
                'tournament[name]' => $this->name,
                'tournament[start_at]' => $this->startDate,
                'tournament[tournament_type]' => $this->challongeTypes[$this->type],
            ]);

            if (!$editAction) {
                $this->appendMessage(new Message('Dit toernooi kon niet worden bijgewerkt. Challonge kan niet worden bereikt.'));
                return false;
            }

        }

        return true;
    }

    /**
     * before save event
     */
    public function beforeSave()
    {
        // remove the tournament menu caching before saving
        $this->getCache()->delete('tournament_menu');
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
        $types = self::getTypes();
        $states = self::getStates();
        $cache = $this->getCache();

        $this->typeString = $types[$this->type];
        $this->stateString = $states[$this->state];


        $this->startDateString = date('d-m-y H:i', strtotime($this->startDate));

        if ($this->challongeId) {
            $this->isChallonge = true;
        }

        // todo fix this logic
        if ($this->isChallonge) {

            $challongeTournament = $this->getChallongeTournament();
            if ($challongeTournament) {

                if (!$this->hasError) {


                    $imageKey = sprintf('tournament_%s_image', $this->systemName);
                    if ($cache->exists($imageKey)) {
                        $image = $cache->get($imageKey);
                    } else {

                        // cache the overview image url for a day
                        $image = (string)$challongeTournament->getOverviewImage();
                        $cache->save($imageKey, $image, 86400);
                    }

                    // generate an image for this tournament
                    if (Util::verifyUrl($image)) {

                        // always remove the old image
                        $newImage = $this->getConfig()->application->cacheDir . $this->systemName . '.png';

                        if (file_exists($newImage)) {
                            unlink($newImage);
                        }

                        $tmpFile = $this->getConfig()->application->cacheDir . $this->systemName . '.png';
                        file_put_contents($tmpFile, file_get_contents((string)$image));

                        $mimeType = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmpFile);
                        if ($mimeType) {
                            if (strpos($mimeType, 'image') !== false) {
                                try {
                                    $original = new \Phalcon\Image\Adapter\GD($tmpFile);
                                    if ($original->getHeight() >= 105) {
                                        $original->crop($original->getWidth(), $original->getHeight(), 0, 105);
                                    }
                                    $original->save($newImage);
                                    chmod($newImage, 0777);
                                } catch(\Exception $e) {
                                    // do nothing
                                }
                            }
                        }

                    }
                }
            }

        }

        $playerArrayKey = sprintf('tournament_%s_playersarray', $this->systemName);
        if ($cache->exists($playerArrayKey)) {
            $this->playersArray = $cache->get($playerArrayKey);
        } else {

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

            $cache->save($playerArrayKey,  $this->playersArray , 600);
            $ranks = [];

            // if the tournament is a non team challonge tournament, get the player ranks
            if ($this->isChallonge && !$this->isTeamTournament()) {
                $challongePlayers = $this->getChallongeTournament()->getPlayers();

                if ($challongePlayers) {
                    foreach ($challongePlayers->participant as $player) {

                        $name = (string)$player->name;
                        $rank = null;

                        if (is_numeric($player->{'final-rank'})) {
                            $rank = $player->{'final-rank'};
                        }

                        $ranks[$name] = $rank;
                    }

                }
            }

            if ($this->type == self::TYPE_TOP_SCORE || ($this->isChallonge && count($ranks) > 0 )) {

                usort($this->playersArray, function ($left, $right) use ($ranks) {

                    if ($this->type == self::TYPE_TOP_SCORE) {

                        if ($left['totalScore'] == $right['totalScore']) {
                            return 0;
                        }

                        return ($left['totalScore'] > $right['totalScore'] ? -1 : 1);
                    } else {

                        $nickNameLeft = $left['user']['nickName'];
                        $nickNameRight = $right['user']['nickName'];

                        if (array_key_exists($nickNameLeft, $ranks) && array_key_exists($nickNameRight, $ranks)) {

                            if ($ranks[$nickNameLeft] == $ranks[$nickNameRight]) {
                                return 0;
                            }

                            return ($ranks[$nickNameLeft] > $ranks[$nickNameRight] ? -1 :1);

                        }

                    }
                });
            }

        }

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
            'isTeamTournament' => 'isTeamTournament',
            'teamSize' => 'teamSize',
            'state' => 'state'
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
     * @return bool
     */
    protected function deleteChallongeTournament()
    {
        $challongeApi = $this->getChallongeAPI();
        $deleteAction = $challongeApi->deleteTournament($this->challongeId);

        if (!$deleteAction) {
            $this->appendMessage(new Message('Dit toernooi kon niet worden verwijderd. Challonge kan niet worden bereikt.'));
            return false;
        }

        $this->challongeId = null;

        return true;
    }

    /**
     * @return bool
     */
    protected function createChallongeTournament()
    {
        // if the tournament is single or double elimination, register the tournament in challonge
        $challongeApi = $this->getChallongeAPI();
        $challongeTournament = $challongeApi->createTournament([
                'tournament[name]' => $this->name,
                'tournament[tournament_type]' => $this->challongeTypes[$this->type],
                'tournament[url]' => $this->systemName,
                'tournament[subdomain]' => $challongeApi->getSubDomain(),
                'tournament[open_signup]' => 'false',
                'tournament[start_at]' => $this->startDate
            ]);

        if (!$challongeTournament) {
            $this->appendMessage(new Message('Dit toernooi kon niet gemaakt worden. Challonge kan niet worden bereikt.'));
            return false;
        }

        $this->challongeId = $this->systemName;

        return true;
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

}

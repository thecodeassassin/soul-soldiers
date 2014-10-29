<?php
namespace Soul\Model;

use BinaryBeast;
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

        if ($this->isChallongeTournament()) {
            return $this->createChallongeTournament();
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

                $binaryBeast = $this->getBinaryBeast();
//                $test = $binaryBeast->tournament();
//                $test->title = 'test';
//
//                $test->save();

            //Loop through and display the matches / results of each group
                $tournament = $binaryBeast->tournament('xHotS1410290');

                die(var_dump($tournament->brackets));

                foreach ($tournament->brackets as $bracket => $rounds): ?>
                    <h2><?php echo ucfirst($bracket); ?></h2>

                    <?php
                    //Loop through each round for this group
                    foreach ($rounds as $round => $matches) {
                        echo '<h3>Round ' . ($round + 1) . '</h3>';

                        //Loop through each match in the round
                        foreach ($matches as $i => $match) {
                            /* @var $match BBMatchObject */

                            echo '<h4>Match ' . ($i + 1) . '</h4>';

                            //Is there a team in the first position?
                            if(!is_null($match->team)) {
                                echo $match->team->display_name;

                                //Do we have an opponent?
                                if(!is_null($match->opponent)) {
                                    //Determine how to print the second half of the match description, based on if the match has been reported
                                    $out = null;

                                    //If we have match details, display the results
                                    if(!is_null($match->match)) {

                                        //Instead of display "team vs opponent", display "team 2:1 opponent" indicating the result and score
                                        if (!is_null($match->match->id)) {
                                            //First team won - display score:o_score
                                            if ($match->match->winner == $match->team) {
                                                $out = ' <b>' . $match->match->score . ':' . $match->match->o_score . '</b> ';
                                            } //Second team won - display o_score:score
                                            else {
                                                $out = ' <b>' . $match->match->o_score . ':' . $match->match->score . '</b> ';
                                            }

                                            $out .= $match->opponent->display_name;
                                        }
                                    }

                                    //Unplayed match - generic "player vs player" output
                                    if (is_null($out)) {
                                        $out = ' vs. ' . $match->opponent->display_name;
                                    }

                                    //Print the result, either player $x:$y player, or player vs player
                                    echo "$out<br />";
                                }

                                //Waiting on an opponent
                                else {
                                    echo ' - Waiting on an opponent<br />';
                                }
                            }

                            //Player in position 2?
                            else if(!is_null($match->opponent)) {
                                //We'd only get to this point if the first position was null, so we know he is waiting on an opponent
                                echo $match->team->display_name . ' - Waiting on an opponent<br />';
                            }
                        }
                    }

                endforeach;

                die;
//                die(var_dump( \BBHelper::embed_tournament($binaryBeast->tournament('xHotS1410290'))));


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


                    $mimeType = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmpFile);
                    if ($mimeType) {
                        if (strpos($mimeType, 'image') !== false) {
                            $original = new \Phalcon\Image\Adapter\GD($tmpFile);
                            if ($original->getHeight() >= 105) {
                                $original->crop($original->getWidth(), $original->getHeight(), 0, 105);
                            }
                            $original->save($newImage);
                            chmod($newImage, 0777);
                        }
                    }

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

        return false;
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
    protected function isChallongeTournament()
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
     * @return BinaryBeast
     */
    protected function getBinaryBeast()
    {
        return $this->getDI()->get('binarybeast');
    }
}

<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Tournament 
 */  

namespace Soul\Tournaments\Challonge;

use Soul\Module;
use Soul\Tournaments\Challonge;

/**
 * Challonge tournament object
 *
 * Class Tournament
 *
 * @package Soul\Tournaments\Challonge
 */
class Tournament extends Module
{

    const STATE_PENDING = 'pending';
    const STATE_UNDERWAY = 'underway';
    const STATE_AWAITING_REVIEW = 'awaiting_review';
    const STATE_COMPLETE = 'complete';

    protected $tournamentObject;

    /**
     * Challonge API object
     *
     * @var Challonge
     */
    protected $api;

    /**
     * Tournament ID in Challonge
     *
     * @var string
     */
    protected $challongeId;

    /**
     * Initialize the tournament
     *
     * @param string $challongeId
     *
     * @throws Exception
     */
    public function __construct($challongeId)
    {

        parent::__construct();

        $this->challongeId = $challongeId;

        try {
            $this->api = $this->getApi();

            $this->tournamentObject = $this->api->getTournament($challongeId);

        } catch (\Exception $e) {
            throw new Exception(sprintf('Tournament with ID %s could not be loaded', $challongeId));
        }

    }

    /**
     * @return bool
     */
    public function isQuickAdvance()
    {
        return ($this->tournamentObject->{'quick-advance'} == 'true');
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->tournamentObject->state;
    }

    /**
     * Return true if the tournament has started
     *
     * @return bool
     */
    public function hasStarted()
    {
        return $this->tournamentObject->state == self::STATE_UNDERWAY;
    }

    /**
     * Returns true if the tournament is completed
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->tournamentObject->state == self::STATE_COMPLETE;
    }


    /**
     * Returns true if the tournament is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->tournamentObject->state == self::STATE_PENDING;
    }

    /**
     * @return bool
     */
    public function isAwaitingReview()
    {
        return $this->tournamentObject->state == self::STATE_AWAITING_REVIEW;
    }

    /**
     * @return bool|\SimpleXMLElement
     * @throws Exception
     */
    public function getMatches()
    {
        try {

            $matches = $this->api->getMatches($this->challongeId);

            if ($matches) {
                return $matches;
            }

            return array();

        } catch (\Exception $e) {
            throw new Exception(sprintf('Could not get matches for tournament with ID %s.', $this->challongeId));
        }

    }


    /**
     * @return bool|\SimpleXMLElement
     * @throws Exception
     */
    public function getMatchesByParticipantId($id)
    {
        try {

            $matches = $this->api->getParticipant($this->challongeId, $id, ['include_matches' => 1]);

            if ($matches) {
                return $matches;
            }

            return array();

        } catch (\Exception $e) {
            throw new Exception(sprintf('Could not get matches for tournament with ID %s.', $this->challongeId));
        }

    }

    /**
     * @param $matchId
     * @return bool|\SimpleXMLElement
     * @throws Exception
     */
    public function getMatchById($matchId)
    {
        try {
            $match = $this->api->getMatch($this->challongeId, $matchId);

            return $match;

        } catch (\Exception $e) {
            throw new Exception(sprintf('No match with ID %s.', $matchId));
        }
    }

    public function getOverviewImage()
    {
        return $this->tournamentObject->{'live-image-url'};
    }

    /**
     * Get the players for this tournament
     *
     * @return bool|\SimpleXMLElement
     *
     * @throws Exception
     */
    public function getPlayers()
    {
        try {
            return $this->api->getParticipants($this->challongeId);

        } catch (\Exception $e) {
            throw new Exception(sprintf('No participants found for tournament %s', $this->challongeId));
        }

    }

    /**
     * @param $name
     * @throws Exception
     */
    public function addPlayer($name)
    {
        try {

            $this->api->createParticipant(
                $this->challongeId,
                [
                    'participant[name]' => $name
                ]
            );

        } catch (\Exception $e) {
            throw new Exception(sprintf('No participants found for tournament %s', $this->challongeId));
        }
    }

    public function start()
    {
        try {

            return$this->api->startTournament($this->challongeId);

        } catch (\Exception $e) {
            throw new Exception(sprintf('Cannot start %s', $this->challongeId));
        }
    }

    public function end()
    {
        try {

            return $this->api->endTournament($this->challongeId);

        } catch (\Exception $e) {
            throw new Exception(sprintf('Cannot end %s', $this->challongeId));
        }
    }

    /**
     * Randomize seeds
     *
     * @throws Exception
     */
    public function randomize()
    {
        try {

            if (!$this->hasStarted()) {
                $this->api->randomizeParticipants($this->challongeId);
            }

        } catch (\Exception $e) {
            throw new Exception(sprintf('Cannot randomize %s', $this->challongeId));
        }
    }

    /**
     * Get the challonge api
     * @return Challonge
     */
    protected function getApi()
    {
        return $this->di->get('challonge');
    }
} 
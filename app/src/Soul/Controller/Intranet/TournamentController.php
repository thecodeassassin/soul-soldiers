<?php
namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;
use Soul\Controller\Base;
use Soul\Model\Tournament;
use Soul\Model\TournamentScore;
use Soul\Model\TournamentTeam;
use Soul\Model\TournamentUser;
use Soul\Model\User;
use Soul\Util;

/**
 * Class IndexController
 *
 * @package Soul\Controller
 *
 */
class TournamentController extends Base
{

    /**
     * Index action
     */
    public function indexAction()
    {
//        $this->view->tournaments = Tournament::getLatestTournaments();
    }

    public function viewAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName) or null;

        if ($tournament) {
            if ($tournament->isTeamTournament()) {
                $teamUserCount = count($tournament->teams) * $tournament->teamSize;
                $userCount = count($tournament->players);

                if ($teamUserCount != $userCount && $this->view->user->isAdmin()) {
                    $this->flashMessage('Het aantal spelers in teams is niet gelijk aan het aantal inschrijvingen, regeneer de teams voordat het toernooi start.', 'error');
                }

            }
        }

        if ($this->getUser()->isAdmin()) {
            if ($tournament->type == Tournament::TYPE_TOP_SCORE) {
                $this->assets->collection('scripts')->addJs('js/intranet/topscore.js');
            } else {
                $this->assets->collection('scripts')->addJs('js/intranet/bracket.js');
                $this->assets->collection('main')->addCss('css/intranet/bracket.css');
                $this->assets->collection('scripts')->addJs('js/intranet/elimination.js');
            }
        }
        $this->view->tournament = $tournament;
    }

    /**
     * @param $systemName
     * @return \Phalcon\Http\ResponseInterface
     */
    public function signupAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);
        if ($tournament) {

            if ($tournament->hasEntered($this->view->user->getUserId())) {
                $this->flashMessage(sprintf('Je bent al ingeschreven voor %s', $tournament->name), 'error', true);

                return $this->response->redirect('tournament/view/'.$systemName);
            }

            // sign the user up for the given tournament
            $tournament->registerPlayer($this->authService->getAuthData()->userId);

            $this->flashMessage(sprintf('Successvol ingeschreven voor %s', $tournament->name), 'success', true);
        }

        return $this->response->redirect('tournament/view/'.$systemName);
    }

    /**
     * @param $systemName
     * @return \Phalcon\Http\ResponseInterface
     */
    public function cancelAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);
        $userId = $this->view->user->getUserId();

        if ($tournament) {

            if (!$tournament->hasEntered($userId)) {
                $this->flashMessage(sprintf('Je bent niet ingeschreven voor %s', $tournament->name), 'error', true);

                return $this->response->redirect('tournament/view/'.$systemName);
            }
            $tournamentUser = TournamentUser::findFirstByTournamentIdAndUserId($tournament->tournamentId, $userId);

            if ($tournamentUser) {
                $deleted = $tournamentUser->delete();

                if (!$deleted) {
                    $this->flashMessages($tournamentUser->getMessages(), 'error', true);
                }

                Tournament::clearCache($tournament);
            }


            $this->flashMessage(sprintf('Successvol uitgeschreven voor %s', $tournament->name), 'success', true);
        }

        return $this->response->redirect('tournament/view/'.$systemName);
    }

    /**
     * @param $userId
     */
    public function addScoreAction($userId)
    {

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $score = new TournamentScore();
        $tournamentUser = TournamentUser::findFirstByTournamentUserId($userId);

        if ($tournamentUser) {
            $scoreCount = $this->request->getPost('scoreCount');

            if (is_numeric($scoreCount) && $scoreCount != 0) {
                $score->tournamentUserId = $tournamentUser->tournamentUserId;
                $score->score = $scoreCount;
                $score->save();

                if (!$this->request->isAjax()) {
                    $this->flashMessage(
                        sprintf('%s punten toegevoegd aan %s', $scoreCount, $tournamentUser->user->nickName), 'success', true
                    );
                }

                // delete the player cache
                Tournament::clearCache($tournamentUser->tournament);
            }
        }

        return $this->response->redirect('tournament/view/'.$tournamentUser->tournament->systemName);

    }

    /**
     * @param $systemName
     */
    public function generateTeamsAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);
        $teams = [];
        $players = [];

        try {

            if (!$tournament->isTeamTournament()) {
                throw new \Exception('Toernooi is geen team toernooi');
            }

            $playerCount = count($tournament->players);

            if (($playerCount % $tournament->teamSize) != 0 ) {
                throw new \Exception(sprintf('Er kunnen geen teams gemaakt worden van %d deelnemers.', $playerCount));
            }

            if ($playerCount / $tournament->teamSize  == 1) {
                throw new \Exception(sprintf('Er dienen minimaal %d mensen mee te doen.', (2 * $tournament->teamSize)));
            }

            // delete all existing teams first
            TournamentTeam::deleteAllByTournamentId($tournament->tournamentId);

            foreach ($tournament->players as $player) {
                $players[] = $player->userId;
            }

            // shuffle the array
            shuffle($players);

            while (count($players) > 0) {

                $newPlayers = array_splice($players, $tournament->teamSize);
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
                $tournamentTeam->tournamentId = $tournament->tournamentId;
                $tournamentTeam->save();

                // add all the players to the team
                foreach ($team as $teamPlayer) {
                    $tournamentUser = TournamentUser::findFirstByTournamentIdAndUserId($tournament->tournamentId, $teamPlayer);
                    $tournamentUser->teamId = $tournamentTeam->teamId;
                    $tournamentUser->save();
                }

                $num += 1;
            }

            $this->flashMessage(sprintf('Aantal teams gegenereerd: %d', count($teams)), 'success', true);

        } catch (\Exception $e) {
            $this->flashMessage(sprintf('Teams kunnen niet worden gegenereerd: %s', $e->getMessage()), 'error', true);

        }

        return $this->response->redirect('tournament/view/'.$systemName);


    }

    /**
     * @param $systemName
     */
    public function startAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);

        if ($tournament) {


            if ($tournament->isTeamTournament() && count($tournament->teams) < $tournament->teamSize || count($tournament->players) < 2) {
                $this->flashMessage('Er zijn nog geen teams aangemaakt, of er zijn onvoldoende spelers!', 'error', true);
            } else {

                $tournament->state = Tournament::STATE_STARTED;
                $tournament->onlyStateUpdate = true;
                $tournament->save();


            }

        }

        return $this->response->redirect('tournament/view/'.$systemName);
    }

    /**
     * @param $systemName
     */
    public function endAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);

        if ($tournament) {


            $tournament->state = Tournament::STATE_FINISHED;
            $tournament->onlyStateUpdate = true;
            $saved = $tournament->save();

            if (!$saved) {
                $this->flashMessages($tournament->getMessages(), 'error', true);
            }
        }

        return $this->response->redirect('tournament/view/'.$systemName);
    }


    /**
     * @param string     $systemName
     * @param string|int $matchId
     */
    public function selectWinnerAction($systemName, $matchId)
    {
//        $tournament = Tournament::findFirstBySystemName($systemName);
//
//        if ($tournament) {
//            $match = $tournament->getChallongeTournament()->getMatchById($matchId);
//
//            die(var_dump($match));
//        }
    }


    /**
     * @param $systemName
     * @param $tournamentUserId
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function removeUserAction($systemName, $tournamentUserId)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);

        $tournamentUser = TournamentUser::findFirstByTournamentUserId($tournamentUserId);

        // if the tournament user is present, either remove the user or disable him
        if ($tournamentUser) {

            if ($tournament->state == Tournament::STATE_STARTED && $tournament->type == Tournament::TYPE_TOP_SCORE) {
                $tournamentUser->active = 0;
                $tournamentUser->save();
            } else {
                $tournamentUser->delete();
            }

            Tournament::clearCache($tournament);

            return $this->response->redirect('tournament/view/'.$tournament->systemName);
        }

    }

    /**
     * @param $tournamentId
     */
    public function updateRankAction($tournamentId)
    {
        $tournament = Tournament::findFirstById($tournamentId);
        $users = $this->request->getPost('userId');

        $tournament->updateRanks($users);
    }

    public function getTournamentData($tournamentId)
    {

    }

    /**
     * @param string $tournamentName
     */
    protected function getChallongeError()
    {
        $this->flashMessage(sprintf('Challonge is onbereikbaar, probeer het later nogmaals.'), 'error', true);
    }

    /**
     * @param $systemName
     */
    public function overviewAction($systemName)
    {


    }

}
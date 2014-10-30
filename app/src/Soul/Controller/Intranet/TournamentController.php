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
                    $this->flashMessage('Het aantal spelers is niet gelijk aan het aantal spelers, regeneer de teams voordat het toernooi start.', 'error');
                }

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
        $participant = null;

        if ($tournament) {

            if ($tournament->hasEntered($this->view->user->getUserId())) {
                $this->flashMessage(sprintf('Je bent al ingeschreven voor %s', $tournament->name), 'error', true);

                return $this->response->redirect('tournament/view/'.$systemName);
            }

            if ($tournament->isChallonge && !$tournament->isTeamTournament()) {
                $challongeTournament = $tournament->getChallongeTournament();

                if (!$challongeTournament) {
                    $this->getChallongeError();
                    return $this->response->redirect('tournament/view/'.$systemName);
                }

                $participant = $challongeTournament->addPlayer($this->view->user->getNickName());
            }

            // sign the user up for the given tournament
            $tournamentUser = new TournamentUser();
            $tournamentUser->userId = $this->authService->getAuthData()->userId;
            $tournamentUser->tournamentId = $tournament->tournamentId;

            if ($participant){
                $tournamentUser->participantId = $participant->id;
            }

            $tournamentUser->active = 1;
            $tournamentUser->save();

            Tournament::clearCache($tournament);

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

            if (($playerCount % $tournament->teamSize) == 1 ) {
                throw new \Exception(sprintf('Er kunnen geen teams gemaakt worden van %d deelnemers.', $playerCount));
            }

            if ($playerCount / $tournament->teamSize  == 1) {
                throw new \Exception(sprintf('Er dienen minimaal %d mensen mee te doen.', (2 * $tournament->teamSize)));
            }

            // delete all existing teams first
            $existingTeams = TournamentTeam::findByTournamentId($tournament->tournamentId);
            foreach ($existingTeams as $existingTeam) {
                $existingTeam->delete();
            }


            foreach ($tournament->players as $player) {
                $players[] = $player->userId;
            }

            // shuffle the array
            shuffle($players);

            while (count($players) > 0) {

                $newPlayers = array_splice($players, 2);
                $teams[] = $players;

                $players = $newPlayers;
            }

            foreach ($teams as $team) {
                $tournamentTeam = new TournamentTeam();
                $teamNames = [];

                foreach ($team as $userId) {
                    $user = User::findFirstByUserId($userId);
                    $teamNames[] = $user->nickName;
                }

                $tournamentTeam->name = implode(' & ', $teamNames);
                $tournamentTeam->tournamentId = $tournament->tournamentId;
                $tournamentTeam->save();

                // add all the players to the team
                foreach ($team as $teamPlayer) {
                    $tournamentUser = TournamentUser::findFirstByTournamentIdAndUserId($tournament->tournamentId, $teamPlayer);
                    $tournamentUser->teamId = $tournamentTeam->teamId;
                    $tournamentUser->save();
                }
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


            if ($tournament->isTeamTournament() && count($tournament->teams) < 2 || count($tournament->players) < 2) {
                $this->flashMessage('Er zijn nog geen teams aangemaakt, of er zijn onvoldoende spelers!', 'error', true);
            } else {

                $tournament->state = Tournament::STATE_STARTED;
                $tournament->onlyStateUpdate = true;
                $tournament->save();

                if ($tournament->isChallonge) {
                    $challongeTournament = $tournament->getChallongeTournament();

                    if (!$challongeTournament) {
                        $this->getChallongeError();
                        return $this->response->redirect('tournament/view/'.$systemName);
                    }

                    if ($tournament->isTeamTournament()) {
                        // create all the teams in challonge
                        foreach ($tournament->teams as $team) {

                            // add a player with the name of the team
                            $challongeTournament->addPlayer($team->name);
                        }
                    }

                    if (!$challongeTournament->hasStarted()) {
                        if ($challongeTournament->start()) {
                            $this->flashMessage(sprintf('Toernooi %s is gestart.', $tournament->name), 'success', true);
                        } else {
                            $this->flashMessage(sprintf('Toernooi %s kan niet worden gestart.', $tournament->name), 'error', true);
                        }
                    }

                }

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

            if ($tournament->isChallonge) {
                $challongeTournament = $tournament->getChallongeTournament();

                if (!$challongeTournament) {
                    $this->getChallongeError();
                    return $this->response->redirect('tournament/view/'.$systemName);
                }

                if ($challongeTournament->isAwaitingReview()) {
                    if ($challongeTournament->endTournament()) {
                        $this->flashMessage(sprintf('Toernooi %s is beeindigd.', $tournament->name), 'success', true);
                    } else {
                        $this->flashMessage(sprintf('Toernooi %s kan niet worden beeindigd.', $tournament->name), 'error', true);
                    }
                }
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
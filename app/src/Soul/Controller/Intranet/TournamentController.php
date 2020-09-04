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

                if ($teamUserCount != $userCount && $this->isIntranetAdmin()) {
                    $this->flashMessage('Het aantal spelers in teams is niet gelijk aan het aantal inschrijvingen, regeneer de teams voordat het toernooi start.', 'error');
                }
            }
        }

        if ($tournament->state == Tournament::STATE_STARTED || $tournament->state == Tournament::STATE_FINISHED) {

            if ($this->isIntranetAdmin()) {

                if ($tournament->isEliminationTournament()) {
                    $this->assets->collection('scripts')->addJs('js/intranet/eliminationAdmin.js');
                }
            }

            if ($tournament->isEliminationTournament()) {
                $this->assets->collection('scripts')->addJs('js/intranet/bracket.js');
                $this->assets->collection('scripts')->addJs('js/intranet/elimination.js');
                $this->assets->collection('main')->addCss('css/intranet/bracket.css');
                $this->assets->collection('main')->addCss('css/intranet/bracket.custom.css');
            }
        }

        if ((($tournament->isEliminationTournament() && !in_array($tournament->state, [Tournament::STATE_STARTED, Tournament::STATE_FINISHED])) || !$tournament->isEliminationTournament()) && $this->isIntranetAdmin()) {

            $this->assets->collection('scripts')->addJs('js/jquery-ui.min.js');
            $this->assets->collection('scripts')->addJs('js/intranet/topscore.js');
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

                return $this->response->redirect('tournament/view/' . $systemName);
            }

            // sign the user up for the given tournament
            $tournament->registerPlayer($this->authService->getAuthData()->userId);

            $this->flashMessage(sprintf('Successvol ingeschreven voor %s', $tournament->name), 'success', true);
        }

        return $this->response->redirect('tournament/view/' . $systemName);
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

                return $this->response->redirect('tournament/view/' . $systemName);
            }
            $tournamentUser = TournamentUser::findFirstByTournamentIdAndUserId($tournament->tournamentId, $userId);

            if ($tournamentUser) {
                $deleted = $tournamentUser->delete();

                if (!$deleted) {
                    $this->flashMessages($tournamentUser->getMessages(), 'error', true);
                }
            }


            $this->flashMessage(sprintf('Successvol uitgeschreven voor %s', $tournament->name), 'success', true);
        }

        return $this->response->redirect('tournament/view/' . $systemName);
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
                        sprintf('%s punten toegevoegd aan %s', $scoreCount, $tournamentUser->user->nickName),
                        'success',
                        true
                    );
                }
            }
        }

        return $this->response->redirect('tournament/view/' . $tournamentUser->tournament->systemName);
    }

    /**
     * @param $systemName
     */
    public function generateTeamsAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);

        try {

            if (!$tournament->isTeamTournament()) {
                throw new \Exception('Toernooi is geen team toernooi');
            }

            $playerCount = count($tournament->players);

            if (($playerCount % $tournament->teamSize) != 0) {
                throw new \Exception(sprintf('Er kunnen geen teams gemaakt worden van %d deelnemers.', $playerCount));
            }

            if ($playerCount / $tournament->teamSize  == 1) {
                throw new \Exception(sprintf('Er dienen minimaal %d mensen mee te doen.', (2 * $tournament->teamSize)));
            }

            $teams = $tournament->generateTeams();

            $this->flashMessage(sprintf('Aantal teams gegenereerd: %d', count($teams)), 'success', true);
        } catch (\Exception $e) {
            $this->flashMessage(sprintf('Teams kunnen niet worden gegenereerd: %s', $e->getMessage()), 'error', true);
        }

        return $this->response->redirect('tournament/view/' . $systemName);
    }

    /**
     * @param $systemName
     */
    public function startAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);
        if ($tournament) {

            if (
                $tournament->isTeamTournament() && count($tournament->players) % $tournament->teamSize != 0
                || count($tournament->players) < 2
            ) {
                $this->flashMessage('Er zijn nog geen teams aangemaakt, of er zijn onvoldoende spelers!', 'error', true);
            } else {

                $tournament->updateBracketData($tournament->isTeamTournament());
                $tournament->start();
            }
        }

        return $this->response->redirect('tournament/view/' . $systemName);
    }

    /**
     * @param $systemName
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function resetAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);
        if ($tournament) {

            if ($tournament->state == Tournament::STATE_STARTED) {

                $tournament->reset();
                $this->flashMessage('Het toernooi is gereset', 'success', true);
            }
        }

        return $this->response->redirect('tournament/view/' . $systemName);
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

        return $this->response->redirect('tournament/view/' . $systemName);
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

            return $this->response->redirect('tournament/view/' . $tournament->systemName);
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

    /**
     * @param $systemName
     */
    public function overviewAction($systemName)
    {
    }

    /**
     * @param $teamId
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function editTeamNameAction($teamId)
    {

        $userId = $this->authService->getAuthData()->userId;
        $isIntranetAdmin = $this->authService->getAuthData()->isIntranetAdmin();
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $team = TournamentTeam::findFirstById($teamId);

        if ($this->request->isPost()) {

            if ($team) {
                $teamName = $this->request->get('teamName');
                $tournament = $team->getTournament();

                if (!$team->userInTeam($userId) && !$isIntranetAdmin) {
                    $this->flashMessage(sprintf('Je bent geen lid van %s', $team->name), 'error', true);
                    return $this->response->redirect('tournament/view/' . $tournament->systemName);
                }

                $team->name = $teamName;
                $saved = $team->save();

                if (!$saved) {
                    $this->flashMessages($team->getMessages(), 'error', true);
                } else {
                    $this->flashMessage(sprintf('Team naam aangepast naar %s', $teamName), 'success', true);
                }

                return $this->response->redirect('tournament/view/' . $tournament->systemName);
            }
        }

        $this->view->team = $team;
        $this->view->teamId = $teamId;
    }
}

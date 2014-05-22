<?php
namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;
use Soul\Controller\Base;
use Soul\Model\Tournament;
use Soul\Model\TournamentScore;
use Soul\Model\TournamentUser;
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
        $this->view->tournaments = Tournament::getLatestTournaments();


    }

    /**
     * @param $systemName
     */
    public function signupAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);

        if ($tournament) {

            if ($tournament->isChallonge) {
                $tournament->challonge->addPlayer($this->view->user->getNickName());

            } else {

                // sign the user up for the given tournament
                $tournamentUser = new TournamentUser();
                $tournamentUser->userId = $this->authService->getAuthData()->userId;
                $tournamentUser->tournamentId = $tournament->tournamentId;
                $tournamentUser->active = 1;

                $tournamentUser->save();

            }

            $this->flashMessage(sprintf('Successvol ingeschreven voor %s', $tournament->name), 'success', true);
            $this->response->redirect('tournaments');
        }
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
            }
        }

        if ($this->request->isAjax()) {

            // refresh score and return new score
            $tournamentUser = TournamentUser::findFirstByTournamentUserId($userId);
            echo $tournamentUser->getTotalScore();
        } else {
            $this->response->redirect('tournaments');
        }
    }

    /**
     * @param $systemName
     */
    public function startAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);

        die;

        if ($tournament && !$tournament->challonge->hasStarted()) {
            if ($tournament->challonge->start()) {
                $this->flashMessage(sprintf('Toernooi %s is gestart.', $tournament->name), 'success', true);
            } else {
                $this->flashMessage(sprintf('Toernooi %s kan niet worden gestart.', $tournament->name), 'error', true);
            }

        }
        $this->response->redirect('tournaments');
    }

    /**
     * @param $systemName
     */
    public function endAction($systemName)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $tournament = Tournament::findFirstBySystemName($systemName);


        if ($tournament && $tournament->challonge->isAwaitingReview()) {
            if ($tournament->challonge->end()) {
                $this->flashMessage(sprintf('Toernooi %s is beeindigd.', $tournament->name), 'success', true);
            } else {
                $this->flashMessage(sprintf('Toernooi %s kan niet worden beeindigd.', $tournament->name), 'error', true);
            }
        }

        $this->response->redirect('tournaments');
    }


    /**
     * @param string     $systemName
     * @param string|int $matchId
     */
    public function selectWinnerAction($systemName, $matchId)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);

        if ($tournament) {
            $match = $tournament->challonge->getMatchById($matchId);

            die(var_dump($match));
        }
    }


    /**
     * @param $userId
     */
    public function removeUserAction($userId)
    {

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $tournamentUser = TournamentUser::findFirstByTournamentUserId($userId);

        if ($tournamentUser) {
            $tournamentUser->active = 0;
            $tournamentUser->save();
        }

    }

    /**
     * @param $systemName
     */
    public function overviewAction($systemName)
    {

        $tournament = Tournament::findFirstBySystemName($systemName);

        if ($tournament) {

            if ($image = (string)$tournament->challonge->getOverviewImage()) {
                $this->response->resetHeaders();
                $this->response->setHeader('Content-Type', 'image/png');

                $tmpFile = $this->config->application->cacheDir . $systemName . '.png';
                file_put_contents($tmpFile, file_get_contents($image));


                $original = new \Phalcon\Image\Adapter\GD($tmpFile);
                $original->crop($original->getWidth(), $original->getHeight() - 150);
                $original->save();

                readfile($tmpFile);
            }
        }

    }

}
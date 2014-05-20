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

            // sign the user up for the given tournament
            $tournamentUser = new TournamentUser();
            $tournamentUser->userId = $this->authService->getAuthData()->userId;
            $tournamentUser->tournamentId = $tournament->tournamentId;
            $tournamentUser->active = 1;

            $tournamentUser->save();

            $this->flashMessage(sprintf('Successvol ingeschreven voor %s', $tournament->name), 'success', true);
            $this->response->redirect('tournament/index');
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

            if (is_numeric($scoreCount) && $scoreCount > 0) {
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
            $this->response->redirect('tournament/index');
        }
    }

    /**
     * @param $systemName
     */
    public function startAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);


        if ($tournament) {

        }
    }

    /**
     * @param $systemName
     */
    public function endAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);


        if ($tournament) {

        }
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
}
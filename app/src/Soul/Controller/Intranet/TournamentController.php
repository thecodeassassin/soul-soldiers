<?php
namespace Soul\Controller\Intranet;

use Soul\Controller\Base;
use Soul\Model\Tournament;
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
}
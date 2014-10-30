<?php
namespace Soul\Controller\Intranet;

use Soul\Controller\Base;
use Soul\Model\Event;
use Soul\Model\Tournament;
use Soul\Model\TournamentUser;
use Soul\Util;

/**
 * Class IndexController
 *
 * @package Soul\Controller
 *
 */
class IndexController extends Base
{

    protected $hasNews = true;

    /**
     * Index action
     */
    public function indexAction()
    {
        if (!strpos(Util::getCurrentUrl(), '/home')) {
            return $this->response->redirect('home');
        }

        $payedForBuffet = null;
        $tournamentsArray = [];
        $userId = $this->getUser()->userId;

        if ($userId) {
            $event = Event::getCurrent();
            $payedForBuffet = $event->hasPayedForBuffet($userId);

            $tournamentsArray = [];
            $tournaments = TournamentUser::findByUserId($userId);

            foreach ($tournaments as $tournamentUser) {
                if ($tournamentUser->tournament) {
                    if ($tournamentUser->tournament->state != Tournament::STATE_FINISHED) {
                        $tournamentsArray[$tournamentUser->tournament->systemName] = $tournamentUser->tournament->name;
                    }
                }
            }

        }

        $this->view->payedForBuffet = $payedForBuffet;
        $this->view->tournaments = $tournamentsArray;
    }

}
<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Intranet;


use Phalcon\Mvc\View;
use Soul\Model\Tournament;

class AdminController extends \Soul\Controller\Website\AdminController
{
    public function indexAction()
    {
        $tournaments = Tournament::find();

        $this->view->tournaments = $tournaments;
    }

    public function manageTournamentAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->tournament = $tournament;

    }
}
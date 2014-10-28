<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Intranet;


use Phalcon\Mvc\View;
use Soul\Form\Intranet\TournamentForm;
use Soul\Model\Tournament;

class AdminController extends \Soul\Controller\Website\AdminController
{

    public function indexAction()
    {

    }


    /**
     *
     */
    public function tournamentsAction()
    {
        $tournaments = Tournament::find();

        $this->view->tournaments = $tournaments;
    }

    /**
     * @param $systemName
     */
    public function manageTournamentAction($systemName)
    {
        $tournament = Tournament::findFirstBySystemName($systemName);
        $this->tournamentForm($tournament);

    }

    /**
     * @param $systemName
     */
    public function addTournamentAction()
    {
        $tournament = new Tournament();
        $this->tournamentForm($tournament);

    }

    /**
     * @param Tournament $tournament
     */
    protected function tournamentForm(Tournament $tournament)
    {
        $tournamentForm = new TournamentForm();
        $hasIdentifier = $tournament->tournamentId;

        if ($this->request->isPost()) {

            $tournamentForm->bind($this->request->getPost(), $tournament);


            // if the form is invalid, show the messages to the user
            if (!$tournamentForm->isValid($this->request->getPost())) {
                $this->flashMessages($tournamentForm->getMessages(), 'error');

            } else {
                if ($tournament->validation() === false) {
                    $this->flashMessages($tournament->getMessages(), 'error');
                } else {

                    // save the tournament and go back to the previous screen
                    $tournament->save();

                    if ($hasIdentifier) {
                        $this->flashMessage('Het toernooi is opgeslagen', 'success');
                    } else {
                        $this->flashMessage('Het toernooi is opgeslagen', 'success', true);
                        $this->response->redirect('admin/tournaments/manage/'.$tournament->systemName);
                    }



                }

            }

        } else {
            if ($tournament) {
                $tournamentForm->setEntity($tournament);
            }
        }


        $this->view->form = $tournamentForm;
        $this->view->tournament = $tournament;
    }
}
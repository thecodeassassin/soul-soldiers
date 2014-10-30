<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Intranet;


use Phalcon\Mvc\View;
use Soul\Form\Intranet\TournamentForm;
use Soul\Model\Tournament;
use Soul\Model\TournamentTeam;

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
     * @param $systemName
     */
    public function deleteTournamentAction($systemName)
    {
        try {

            $tournament = Tournament::findFirstBySystemName($systemName);

            if (!$tournament) {
                $this->flashMessage(sprintf('Tournooi %s bestaat niet', $systemName), 'error', true);
            } else {
                // delete the tournament
                $deleteStatus = $tournament->delete();

                if ($deleteStatus) {
                    $this->flashMessage(sprintf('Tournooi %s is verwijderd', $tournament->name), 'success', true);
                } else {
                    $this->flashMessages($tournament->getMessages(), 'error');
                }

            }

            return $this->response->redirect('admin/tournaments');


        } catch (\Exception $e) {
            $this->flashMessage(sprintf('Tournooi verwijderen mislukt: %s', $e->getMessage()), 'error');
        }
    }

    /**
     * @param $teamId
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function editTeamNameAction($teamId)
    {

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        if ($this->request->isPost()) {
            $team = TournamentTeam::findFirstById($teamId);

            if ($team) {
                $teamName = $this->request->get('teamName');
                $tournament = $team->getTournament();

                $team->name = $teamName;
                $saved = $team->save();

                if (!$saved) {
                    $this->flashMessages($team->getMessages(), 'error', true);
                } else {
                    $this->flashMessage(sprintf('Team naam aangepast naar %s', $teamName), 'success', true);
                }

                return $this->response->redirect('tournament/view/'.$tournament->systemName);
            }

        }

        $this->view->teamId = $teamId;

    }

    /**
     * @param Tournament $tournament
     */
    protected function tournamentForm(Tournament $tournament)
    {
        $tournamentForm = new TournamentForm();

        if ($this->request->isPost()) {


            $tournamentForm->bind($this->request->getPost(), $tournament);

            // if isTeamTournament is empty, set the value to 0
            if (!$this->request->has('isTeamTournament')) {
                $tournament->setTeamTournament(false);
            }


            // if the form is invalid, show the messages to the user
            if (!$tournamentForm->isValid($this->request->getPost())) {
                $this->flashMessages($tournamentForm->getMessages(), 'error');

            } else {
                if ($tournament->validation() === false) {
                    $this->flashMessages($tournament->getMessages(), 'error');
                } else {

                    // save the tournament and go back to the previous screen
                    $saveState = $tournament->save();

                    if ($saveState) {

                        $this->flashMessage('Het toernooi is opgeslagen', 'success', true);

                        return $this->response->redirect('admin/tournaments');
                    } else {
                        $this->flashMessages($tournament->getMessages(), 'error');
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
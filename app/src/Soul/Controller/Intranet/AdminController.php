<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Intranet;


use Phalcon\Mvc\Model\Message;
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
     * Update tournament bracket data
     */
    public function updateTournamentDataAction()
    {
        $data = $this->request->getPost('data');
        $tournamentId = $this->request->getPost('tournamentId');

        $tournament = Tournament::findFirstById($tournamentId);

        if ($tournament && $data) {
            $tournament->updateBracketData(false, $data);
        }

    }

    /**
     * @param $systemName
     * @param $count
     */
    public function generatePlayersAction($systemName, $count)
    {

        $tournament = Tournament::findFirstBySystemName($systemName);
        if ($tournament) {
            $tournament->deletePlayers();
            $tournament->generatePlayers($count);
        }

        return $this->response->redirect('tournament/view/'.$systemName);
    }

    /**
     * @param Tournament $tournament
     */
    protected function tournamentForm(Tournament $tournament)
    {
        $tournamentForm = new TournamentForm();
        $error = false;


        // add ckeditor JS
        $this->assets->collection('scripts')->addJs('js/intranet/ckeditor/ckeditor.js');
        $this->assets->collection('scripts')->addJs('js/datepicker.min.js');

        if ($this->request->isPost()) {

            $backToTournament = $this->request->getPost('back');
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

                    if ($tournament)

                    if ($this->request->hasFiles()) {
                        $files = $this->request->getUploadedFiles();

                        foreach ($files as $file) {

                            if (in_array($file->getRealType(), ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'])) {
                                $tournament->image = file_get_contents($file->getTempName());
                            } else {
                                $this->flashMessage('De afbeelding is in een ongeldig formaat, probeer png of jpeg', 'error');
                                $error = true;
                            }
                        }
                    }

                    $saveState = false;
                    if (!$error) {
                        // save the tournament and go back to the previous screen
                        $saveState = $tournament->save();
                    }

                    if ($saveState && !$error) {

                        $this->flashMessage('Het toernooi is opgeslagen', 'success');

                        if ($backToTournament) {
                            return $this->response->redirect('tournament/view/' . $tournament->systemName);
                        }

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
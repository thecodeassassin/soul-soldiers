<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;

use Phalcon\Mvc\View;
use Soul\AclBuilder;
use Soul\Model\Event;
use Soul\Model\User;
use Soul\Util;

/**
 * Class AdminController
 *
 * @package Soul\Controller
 */
class AdminController extends \Soul\Controller\Base
{
    public function initialize()
    {
        parent::initialize();

    }

    /**
     *
     */
    public function indexAction()
    {
        $this->view->page = 'index';
    }

    public function massMailAction()
    {
        $this->view->page = 'massmail';
    }

    public function usersAction()
    {
        $this->view->page = 'users';

        $event = Event::getCurrent();

        $users = User::find();

        $this->view->event = $event;
        $this->view->users = $users;
    }


    public function deleteUserAction($userId)
    {
        $user = $this->validateUserId($userId);

        if (!$user) {
            $this->flashMessage('Ongeldige gebruiker', 'error', true);
        }

        if ($user->userType == AclBuilder::ROLE_ADMIN) {
            $this->flashMessage('Admins kunnen niet verwijderd worden (sorry :P)', 'error', true);
        } else {
            $user->delete();
            $this->flashMessage(sprintf('Gebruiker %s verwijderd', $user->nickName), 'success', true);
        }

        $this->response->redirect('admin/users');
    }

    /**
     * @param $userId
     */
    public function editUserAction($userId)
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $user = $this->validateUserId($userId);


    }

    /**
     * @param $userId
     *
     * @return null|User
     */
    protected function validateUserId($userId)
    {
        $userId = $this->filter->sanitize($userId, 'int');
        $user = null;

        if (!$userId) {
            $this->flashMessage('Ongeldige gebruiker id', 'error', true);
        } else {
            $user = User::findFirstByUserId($userId);
        }

        return $user;
    }
}
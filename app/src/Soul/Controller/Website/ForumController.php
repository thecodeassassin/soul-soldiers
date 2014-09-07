<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ForumController
 */

namespace Soul\Controller\Website;

use Phalcon\Mvc\View;
use Soul\AclBuilder;
use Soul\Auth\AuthService as AuthService;
use Soul\Controller\AccountBase;
use Soul\Form\AccountInformationForm;
use Soul\Form\ChangePasswordForm;
use Soul\Form\ForgotPasswordForm;
use Soul\Form\LoginForm;
use Soul\Form\RegistrationForm;
use Soul\Model\FailedAttempt;
use Soul\Model\ForumCategory;
use Soul\Model\ForumPost;
use Soul\Model\User;
use Soul\Auth\Exception as AuthException;
use Soul\Auth\Data as AuthData;
use Soul\Util;
use Soul\Security\Exception as SecurityException;

/**
 * Class ForumController
 * @package Soul\Controller
 */
class ForumController extends AccountBase
{

    protected $isAdmin = false;

    public function initialize()
    {
        parent::initialize();

        if ($this->authService->getAuthData()->userType == AclBuilder::ROLE_ADMIN) {
            $this->isAdmin = true;
        }
    }

    public function indexAction()
    {

        if ($this->isAdmin) {
            $categories = ForumCategory::find();
        } else {
            $categories = ForumCategory::find([("adminOnly = 0")]);
        }

        $firstCategory = $categories->getFirst();

        $this->view->posts = $firstCategory->getPosts();
        $this->view->categories = $categories;
    }

    /**
     * @param $categoryId
     *
     * @throws \Exception
     */
    public function postsAction($categoryId)
    {

        if (!$this->request->isAjax()) {
            throw new \Exception('Ajax only content!');
        }

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $category = ForumCategory::findFirst(array("categoryId = $categoryId"));

        if ($category->isAdminOnly() && !$this->isAdmin) {
            $this->response->setStatusCode(401, 'Not authenticated');
        } else {

            $this->view->pick('forum/topics');
            $this->view->posts = $category->posts;

        }

    }

    public function readAction($title)
    {

        try {
            $title = urldecode($this->filter->sanitize($title, 'string'));

            $forumPost = ForumPost::findByTitle($title);

            if (!$forumPost || $forumPost->replyId != null) {
                throw new SecurityException('Dit artikel bestaat niet');
            }

            if ($forumPost->category->isAdminOnly() && !$this->isAdmin ) {
                throw new SecurityException('U heeft geen toegang om dit bericht te bekijken');
            }

            $this->view->post = $forumPost;


        } catch (SecurityException $e) {
            $this->flashMessage($e->getMessage(), 'error', true);
            return $this->response->redirect('forum');
        }


    }

}
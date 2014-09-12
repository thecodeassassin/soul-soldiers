<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ForumController
 */

namespace Soul\Controller\Website;

use Phalcon\Logger;
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

        if ($this->view->user->userType == AclBuilder::ROLE_ADMIN) {
            $this->isAdmin = true;
        }

        $this->view->isAdmin = $this->isAdmin;
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

    /**
     * @param $title
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function readAction($postId)
    {
        try {
            $title = urldecode($this->filter->sanitize($title, 'string'));

            $forumPost = ForumPost::findFirstByPostId($postId);

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

    public function changeAction()
    {
        $logger = $this->getLogger();

        try {
            $postId = $this->request->get('postId', 'int');
            $postTitle = $this->request->get('postTitle', 'string');

            $forumPost = ForumPost::findFirstByPostId($postId);

            if (!$forumPost) {
                throw new \Exception(sprintf('Post with id %d not found!', $postId));
            }

            if (!$this->isAdmin && $forumPost->userId != $this->view->user->userId ) {
                throw new SecurityException('Je bent niet bevoegd om deze post aan te passen!');
            }

            if ($postTitle == $forumPost->title || empty($postTitle)) {
                throw new \Soul\Form\Exception('De titel is hetzelfde of de titel is niet in orde.');
            }

            $forumPost->title = $postTitle;
            $forumPost->save();

            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            echo $postTitle;

        } catch (SecurityException $e) {
            $this->response->setStatusCode(401, $e->getMessage());
        } catch (\Soul\Form\Exception $e) {
            $this->response->setStatusCode(500, $e->getMessage());
        } catch (\Exception $e) {
            $this->response->setStatusCode(401, 'Helaas, het opslaan van de titel is niet gelukt. Probeer het a.u.b. nogmaals of neem contact met ons op.');
            $logger->log(Logger::ALERT, $e->getMessage());
        }


    }

}
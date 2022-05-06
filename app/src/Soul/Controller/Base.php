<?php

namespace Soul\Controller;

use Phalcon\Cache\Backend;
use Phalcon\Cache\BackendInterface;
use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\DI;
use Phalcon\Logger;
use Phalcon\Logger\Adapter;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Validation\Message\Group;
use Soul\AclBuilder;
use Soul\Auth\AuthService;
use Soul\Cms\Editor;
use Soul\Form\NewsAddForm;
use Soul\Mail;
use Soul\Menu;
use Soul\Menu\Builder;
use Soul\Model\News;
use Soul\Model\User;
use Soul\Translate;
use Soul\Util;
use Soul\Security\Exception as SecurityException;

/**
 * Base controller for all controllers
 *
 * Class ControllerBase
 */
class Base extends Controller
{

    protected $title = null;

    /**
     * @var Menu
     */
    protected $menu = null;


    protected $translate = null;

    /**
     * @var AuthService
     */
    protected $authService = null;

    /**
     * @var Config
     */
    protected $config = null;

    /**
     * @var Menu
     */
    protected $userMenu;

    /**
     * @var bool
     */
    protected $editable = false;

    /**
     * @var bool page has news
     */
    protected $hasNews = false;

    /**
     * @var NewsAddForm
     */
    protected $newsAddForm;

    public function initialize()
    {
        $this->title = $this->getConfig()->application->{ACTIVE_MODULE}->baseTitle;
        $this->setTitle($this->title);
        $this->setMenu($this->di->get('menu'));

        $this->authService = $this->di->get('auth');

        $this->view->setVar('analyticsCode', $this->getConfig()->analytics->code);
        $this->view->setVar('menu', $this->getMenu()->outputHTML());

        $this->view->user = $this->authService->getAuthData();
        $this->config = $this->getConfig();
        $this->view->module = ACTIVE_MODULE;
        $this->view->editMode = ($this->request->has('editMode') ? true : false);

        if ($this->hasNews) {
            $this->view->news = News::getByModule(ACTIVE_MODULE);

            $this->newsAddForm = new NewsAddForm();
            $this->view->newsaddform = $this->newsAddForm;
        }

        if (ACTIVE_MODULE == 'intranet') {
            $menuConfig = $this->di->get('menuconfig');
            $this->userMenu = Builder::build($menuConfig['intranet-user'], false, array(), '\Soul\Menu\Dropdown');

            $this->view->usermenu = $this->userMenu->outputHTML();
        }


        if ($this->authService->isLoggedIn()) {

            if ($this->editable && $this->authService->getUserType() == AclBuilder::ROLE_ADMIN) {
                $editor = new Editor($this);

                if ($this->request->has('edit')) {
                    $editor->edit();
                }

                if ($this->request->isPost() && $this->request->hasPost('content')) {
                    $editor->save();
                }
            }
        }
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function addNewsAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $post = $this->request->getPost();
        $newPost = new News();

        if ($this->request->isPost()) {

            if ($this->newsAddForm->isValid($this->request->getPost()) == false) {
                $this->flashMessages($this->newsAddForm->getMessages(), 'error');
            } else {

                $newPost->body = $post['body'];
                $newPost->title = $post['title'];

                // create the new user
                $newPost->module = ACTIVE_MODULE;

                $newPost->published = date('Y-m-d H:i:s', time());

                if (!$newPost->save()) {
                    $this->flashMessages($newPost->newsAddForm->getMessages(), 'error');
                }

                $this->flashMessage('Nieuws artikel geplaatst', 'success');

                return $this->response->redirect('home');
            }
        }
    }

    public function editNewsAction()
    {
        $postData = $this->request->getPost();

        if (array_key_exists('newsId', $postData)) {

            if ($newsItem = News::findFirstByNewsId($postData['newsId'])) {

                $newsItem->body = $postData['content'];
                $newsItem->title = $postData['title'];

                $newsItem->save();

                $this->flashMessage('Nieuws item succesvol aangepast', 'success');
                return $this->response->redirect('home');
            }
        }
    }

    /**
     * @param $newsId
     */
    public function deleteNewsAction($newsId)
    {
        if ($newsItem = News::findFirstByNewsId($newsId)) {

            // delete the news item
            $newsItem->delete();

            $this->flashMessage('Nieuws item verwijderd', 'success');

            return $this->response->redirect('home');
        }
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->di->get('config');
    }

    /**
     * Set the title of the page
     *
     * @param string $title Title of the page
     */
    protected function setTitle($title)
    {
        $this->title = $title;
        $this->view->setVar('title', $title);
    }

    /**
     * @param Menu $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param Translate $translate Translate object
     */
    public function setTranslate(Translate $translate)
    {
        $this->translate = $translate;
    }

    /**
     * @return Translate
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    protected function setLastPage($forceCurrent = false)
    {
        if ($forceCurrent) {

            $this->session->set('referer', Util::getCurrentUrl());
            return true;
        }

        if (array_key_exists('HTTP_REFERER', $_SERVER)) {

            $referer = $_SERVER['HTTP_REFERER'];

            // do not overwrite if the user simply refreshes
            if ($referer != Util::getCurrentUrl() && Util::strposa($referer, ['login', 'register']) === false) {
                $this->session->set('referer', $referer);
            }
        }
    }

    /**
     * @return mixed
     */
    protected function getLastPage()
    {
        return $this->session->get('referer');
    }

    /*
     *
     */
    protected function removeLastPage()
    {
        $this->session->remove('referer');
    }

    /**
     * Redirect to the last known page
     */
    protected function redirectToLastPage()
    {
        $lastPage = $this->getLastPage();
        $this->removeLastPage();

        if (is_null($lastPage) || $lastPage == Util::getCurrentUrl()) {
            return $this->response->redirect($this->url->get('home'), true);
        }

        return $this->response->redirect($lastPage, true, 200);
    }

    /**
     * Flash an array of messages
     *
     * @param array|\stdClass |Group $messages
     * @param string          $type
     * @param bool            $isSession
     */
    protected function flashMessages($messages, $type = 'error', $isSession = false)
    {
        if ($isSession) {
            $this->view->disable();
        }

        $output = '';
        foreach ($messages as $message) {
            $output .= "&nbsp;<span class='icon-angle-circled-right'></span>&nbsp; $message <br />";
        }
        $this->flash->$type($output);
    }

    /**
     * @param string $message
     * @param string $type
     * @param bool   $isSession
     */
    public function flashMessage($message, $type, $isSession = false)
    {
        $this->flashMessages([$message], $type, $isSession);
    }

    /**
     * @return Mail
     */
    protected function getMail()
    {
        return $this->di->get('mail');
    }

    /**
     * @return Crypt
     */
    protected function getCrypt()
    {
        return $this->di->get('crypt');
    }

    /**
     * @param $resource
     */
    protected function staticResource($resource)
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $type = strtolower(array_pop(explode('.', $resource)));
        $primaryType = 'text';

        if (in_array($type, ['png', 'jpg', 'jpeg', 'gif'])) {
            $primaryType = 'image';
        }

        $this->response->setHeader('Content-Type', sprintf('%s/%s', $primaryType, $type));

        $cacheDir = $this->config->application->cacheDir;

        $resourceLocation = sprintf('%s/%s', $cacheDir, $resource);

        if (file_exists($resourceLocation)) {
            echo file_get_contents($resourceLocation);
        } else {
            echo '<!-- file not available -->';
        }
    }

    /**
     * @return Adapter
     */
    protected function getLogger()
    {
        return $this->di->get('logger');
    }

    /**
     * @return BackendInterface
     */
    protected function getCache()
    {
        return $this->getDI()->get('cache');
    }

    /**
     * @return \Soul\Auth\Data
     */
    protected function getUser()
    {
        return $this->authService->getAuthData();
    }

    /**
     * @return bool
     */
    protected function isAdmin()
    {
        $user = $this->getUser();
        $result = false;

        if ($user) {
            $result = $user->isAdmin();
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function isIntranetAdmin()
    {
        $user = $this->getUser();
        $result = false;

        if ($user) {
            $result = $user->isIntranetAdmin();
        }

        return $result;
    }
}

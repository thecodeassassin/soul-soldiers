<?php

namespace Soul\Controller;

use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Mvc\Controller;
use Soul\Auth\AuthService;
use Soul\Mail;
use Soul\Menu;
use Soul\Translate;
use Soul\Util;

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


    public function initialize()
    {
        $this->title = $this->getConfig()->application->baseTitle;
        $this->setTitle($this->title);
        $this->setMenu($this->di->get('menu'));

        $this->authService = $this->di->get('auth');

        $this->view->setVar('analyticsCode', $this->getConfig()->analytics->code);
        $this->view->setVar('menu', $this->getMenu()->outputHTML());

        $this->view->user = $this->authService->getAuthData();
        $this->config = $this->getConfig();

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
     * @param array|\stdClass $messages
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

}

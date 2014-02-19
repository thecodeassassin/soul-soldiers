<?php

namespace Soul\Controller;

use Phalcon\Config;
use Phalcon\Mvc\Controller;
use Soul\Menu;
use Soul\Translate;

/**
 * Base controller for all controllers
 *
 * Class ControllerBase
 */
class BaseController extends Controller
{

    protected $title = null;

    /**
     * @var Menu
     */
    protected $menu = null;


    protected $translate = null;


    public function initialize()
    {
        $this->title = $this->getConfig()->application->baseTitle;
        $this->setTitle($this->title);
        $this->setMenu($this->di->get('menu'));


        // too much work to do translations for this project
//        $this->setTranslate($this->di->get('translate'));

//
//        $this->dispatcher->getActiveController();
       $this->view->setVar('menu', $this->getMenu()->outputHTML());
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

}

<?php

namespace Soul\Controller;

use Phalcon\Config;
use Phalcon\Mvc\Controller;

/**
 * Base controller for all controllers
 *
 * Class ControllerBase
 */
class BaseController extends Controller
{

    protected $title = null;

    public function initialize()
    {
        $this->title = $this->getConfig()->application->baseTitle;
        $this->setTitle($this->title);
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->getDI()->get('config');
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
}

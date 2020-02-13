<?php
namespace Soul\Controller\Website;

use Soul\Controller\Base;
use Soul\Model\Event;

/**
 * Class IndexController
 *
 * @package Soul\Controller
 *
 */
class IndexController extends Base
{

    protected $hasNews = true;

    /**
     * Index action
     */
    public function indexAction()
    {
        setlocale(LC_TIME, 'nl_NL');

        $event = Event::getCurrent();
        
        $this->view->event = $event;
    }

    /**
     * News action
     */
    public function newsAction()
    {

    }

}
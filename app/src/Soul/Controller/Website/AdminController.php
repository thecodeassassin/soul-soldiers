<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;

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
}
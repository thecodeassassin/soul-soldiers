<?php
namespace Soul\Controller\Intranet;

use Soul\Controller\Base;
use Soul\Util;

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
        if (!strpos(Util::getCurrentUrl(), '/home')) {
            return $this->response->redirect('home');
        }
    }

}
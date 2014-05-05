<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Website;


/**
 * Class ContentController
 * i
 * @package Soul\Controller
 */
class ContentController extends \Soul\Controller\Base
{
    /**
     * @param $name
     *
     */
    public function showAction($name)
    {
        $this->view->pick('content/'.$name);
    }
}
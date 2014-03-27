<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller;


/**
 * Class ContentController
 * i
 * @package Soul\Controller
 */
class ContentController extends Base
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
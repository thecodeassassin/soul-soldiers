<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package StyleController
 */

namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;


/**
 * Class StaticController
 *
 * @package Soul\Controller
 */
class StaticController extends \Soul\Controller\Base
{
    /**
     * Parse the requested static resource
     *
     * @param $resource
     */
    public function indexAction($resource)
    {
        parent::staticResource($resource);
    }
}
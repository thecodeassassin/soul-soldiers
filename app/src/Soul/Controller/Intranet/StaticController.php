<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package StyleController
 */

namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;
use Soul\Controller\Base;


/**
 * Class StaticController
 *
 * @package Soul\Controller
 */
class StaticController extends Base
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
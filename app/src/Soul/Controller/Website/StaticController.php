<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package StyleController
 */

namespace Soul\Controller\Website;

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
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $type = strtolower(array_pop(explode('.', $resource)));
        $this->response->setHeader('Content-Type', sprintf('text/%s', $type));

        $cacheDir = $this->config->application->cacheDir;

        $resourceLocation = sprintf('%s/%s', $cacheDir, $resource);

        if (file_exists($resourceLocation)) {
            echo file_get_contents($resourceLocation);
        } else {

            echo '<!-- file not available -->';
        }
    }
}
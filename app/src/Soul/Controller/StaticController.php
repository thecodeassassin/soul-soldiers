<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package StyleController 
 */  

namespace Soul\Controller;


 
class StaticController extends Base
{
    /**
     * @param $resource
     */
    public function indexAction($resource)
    {
        die(var_dump($resource));
    }
} 
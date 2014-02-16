<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Menu 
 */

namespace Soul;


/**
 * Menu generator
 *
 * Class Menu
 * @package Soul
 */
class Menu extends Module
{
    /**
     * @param array $menu
     */
    public function __construct(array $menu)
    {
        parent::__construct();

//        die(var_dump($this->dispatcher->getControllerName()));
    }
} 
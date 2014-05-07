<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Menu;

use Soul\Menu;

/**
 * Class Dropdown
 *
 * @package Soul\Menu
 */
class Dropdown extends Menu
{
    /**
     * @var string
     */
    public $menuWrapper = "%s";

    /**
     * classes to be applied to the main menu
     *
     * @var array
     */
    public $menuClasses = ['dropdown-menu'];

    /**
     * @var array
     */
    public $subMenuClasses = ['subMenu'];


    /**
     * optional separator element
     *
     * @var string
     */
    public $separator = "<li class='divider'></li>";

    /**
     * @var array
     */
    public $menuLIClasses = ['primary'];
}
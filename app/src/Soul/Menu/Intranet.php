<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Menu;

use Soul\Menu;

/**
 * Class Intranet
 *
 * @package Soul\Menu
 */
class Intranet extends Menu
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
    public $menuClasses = [];

    /**
     * @var array
     */
    public $subMenuClasses = ['subMenu'];


    /**
     * optional separator element
     *
     * @var string
     */
    public $separator = "<li class='sep'></li>";

    /**
     * @var array
     */
    public $menuLIClasses = ['primary'];
}
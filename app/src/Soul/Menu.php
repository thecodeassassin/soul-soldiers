<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul;

/**
 * Menu object
 *
 * Class Object
 *
 * @package Soul
 */
class Menu
{
    /**
     * @var array
     */
    protected $links = [];

    /**
     * @var string
     */
    public $menuWrapper = "<div class='collapse navbar-collapse' id='mainMenu'>%s</div>";

    /**
     * classes to be applied to the main menu
     *
     * @var array
     */
    public $menuClasses = ['nav', 'navbar-nav', 'pull-right'];

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
    public $primaryLinkClasses = ['primary', 'braces'];

    /**
     * @var array
     */
    public $subMenuLinkClasses = [];


    /**
     * @param string $name       Name of the link
     * @param array  $properties Properties of the menu link
     *
     * @return $this
     */
    public function addLink($name, array $properties)
    {
        $this->links[$name] = $properties;

        return $this;
    }

    /**
     * @param string     $mainMenu name of the main menu
     * @param \Soul\Menu $subMenu  Sub menu
     *
     * @return $this
     */
    public function addSubMenu($mainMenu, Menu $subMenu)
    {
        $this->links[$mainMenu]['subMenu'] = $subMenu;
        $this->links[$mainMenu]['class'][] ='hasSubMenu';

        return $this;
    }

    /**
     * @param string $name Name of the link
     *
     * @return mixed
     */
    public function getLinkByName($name)
    {
        return $this->links[$name];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param array $links
     *
     * @return $this
     */
    public function setLinks(array $links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return string
     */
    public function outputHTML()
    {
        return static::parseHTML($this);
    }


    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     *  Output this menu as HTML
     *
     * @param Menu $menu      Menu to parse
     * @param bool $isSubMenu Is this menu a subMenu
     *
     * @return string
     */
    public static function parseHTML(Menu $menu = null, $isSubMenu = false)
    {


        $classes = ($isSubMenu ? $menu->subMenuClasses : $menu->menuClasses);
        $output = "\n<ul class='".implode(' ', $classes)."'>\n";

        $links = $menu->getLinks();
        $linkCount = count($links);

        $count = 0;
        foreach ($links as $name => $properties) {

            $subMenuHTML = '';
            $lastItem = ++$count == $linkCount;

            $linkClasses = ($isSubMenu ? $menu->subMenuLinkClasses : $menu->primaryLinkClasses);
            if ($lastItem) {
                $linkClasses[] = 'last';
            }

            if (array_key_exists('subMenu', $properties)) {
                $subMenuHTML = static::parseHTML($properties['subMenu'], true);
            }

            $link = sprintf("\n<a href='%s' class='%s'>%s</a>", $properties['link'], implode(' ', $properties['class']), $name);
            $output .= sprintf("\n\n<li class='%s'>
                %s
                %s
            </li>", implode(' ', $linkClasses), $link, $subMenuHTML);

            if (!$lastItem && !$isSubMenu) {
                $output .= $menu->getSeparator();
            }

        }

        if (!$isSubMenu) {
            $output = sprintf($menu->menuWrapper, $output);
        }

        $output .= '</ul>';

        return $output;
    }

}
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
    protected $menuWrapper = "<div class='collapse navbar-collapse' id='mainMenu'>%s</div>";

    /**
     * classes to be applied to the main menu
     *
     * @var array
     */
    protected $menuClasses = ['nav', 'navbar-nav', 'pull-right'];

    /**
     * @var array
     */
    protected $subMenuClasses = ['subMenu'];


    /**
     * optional separator element
     *
     * @var string
     */
    protected $separator = "<li class='sep'></li>";

    /**
     * @var array
     */
    protected $primaryLinkClasses = ['primary'];

    /**
     * @var array
     */
    protected $subMenuLinkClasses = [];


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
     * @param array $menuClasses
     */
    public function setMenuClasses($menuClasses)
    {
        $this->menuClasses = $menuClasses;
    }

    /**
     * @return array
     */
    public function getMenuClasses()
    {
        return $this->menuClasses;
    }

    /**
     * @param string $menuWrapper
     */
    public function setMenuWrapper($menuWrapper)
    {
        $this->menuWrapper = $menuWrapper;
    }

    /**
     * @return string
     */
    public function getMenuWrapper()
    {
        return $this->menuWrapper;
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
     * @param array $subMenuClasses
     */
    public function setSubMenuClasses($subMenuClasses)
    {
        $this->subMenuClasses = $subMenuClasses;
    }

    /**
     * @return array
     */
    public function getSubMenuClasses()
    {
        return $this->subMenuClasses;
    }

    /**
     * @param array $primaryLinkClasses
     */
    public function setPrimaryLinkClasses($primaryLinkClasses)
    {
        $this->primaryLinkClasses = $primaryLinkClasses;
    }

    /**
     * @return array
     */
    public function getPrimaryLinkClasses()
    {
        return $this->primaryLinkClasses;
    }

    /**
     * @param array $subMenuLinkClasses
     */
    public function setSubMenuLinkClasses($subMenuLinkClasses)
    {
        $this->subMenuLinkClasses = $subMenuLinkClasses;
    }

    /**
     * @return array
     */
    public function getSubMenuLinkClasses()
    {
        return $this->subMenuLinkClasses;
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


        $classes = ($isSubMenu ? $menu->getSubMenuClasses() : $menu->getMenuClasses());
        $output = "\n<ul class='".implode(' ', $classes)."'>\n";

        $links = $menu->getLinks();
        $linkCount = count($links);

        $count = 0;
        foreach ($links as $name => $properties) {

            $subMenuHTML = '';
            $lastItem = ++$count == $linkCount;

            $linkClasses = ($isSubMenu ? $menu->getSubMenuLinkClasses() : $menu->getPrimaryLinkClasses());
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
            $output = sprintf($menu->getMenuWrapper(), $output);
        }

        $output .= '</ul>';

        return $output;
    }

}
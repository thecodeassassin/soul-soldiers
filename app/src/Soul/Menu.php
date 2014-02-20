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
    public $menuLIClasses = ['primary', 'braces'];

    /**
     * @var array
     */
    public $subMenuLIClasses = [];

    /**
     * @var array
     */
    public $menuLinkClasses = [];

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
            $parsedProperties = $menu->parseProperties($properties, $lastItem, $isSubMenu);


            if ($parsedProperties['hasSubMenu']) {
                $subMenuHTML = static::parseHTML($properties['subMenu'], true);
            }

            $link = sprintf("\n<a href='%s' class='%s'>%s</a>", $properties['link'],
                implode(' ', $parsedProperties['linkClasses']),
                $name
            );
            $output .= sprintf("\n\n<li class='%s'>
                %s
                %s
            </li>", implode(' ', $parsedProperties['listItemClasses']), $link, $subMenuHTML);

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

    /**
     * Parse the properties of a link item
     *
     * @param array $properties the properties of the given list item
     * @param bool  $isLastItem true if the link is the last one in the list
     * @param bool  $inSubMenu  item is being processed inside a submenu
     *
     * @return array
     */
    public function parseProperties(array $properties, $isLastItem = false, $inSubMenu = false)
    {
        $parsedProperties = [
            'hasSubMenu' => false,
            'linkClasses' => [],
            'listItemClasses' => []
        ];

        $parsedProperties['linkClasses'] = $properties['class'];

        if (array_key_exists('subMenu', $properties)) {
            $parsedProperties['hasSubMenu'] = true;

        }

        if ($inSubMenu) {
            $parsedProperties['linkClasses'] = array_merge($parsedProperties['linkClasses'], $this->subMenuLinkClasses);
            $parsedProperties['listItemClasses'] = $this->subMenuLIClasses;
        } else {
            $parsedProperties['linkClasses'] = array_merge($parsedProperties['linkClasses'], $this->menuLinkClasses);
            $parsedProperties['listItemClasses'] = $this->menuLIClasses;
        }

        if ($isLastItem) {
            $parsedProperties['listItemClasses'][] = 'last';
        }

        return $parsedProperties;
    }

}
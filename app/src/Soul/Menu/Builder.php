<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Menu
 */

namespace Soul\Menu;

use Phalcon\Mvc\Url;
use Soul\Menu;
use Soul\Util;

/**
 * Menu generator
 *
 * Class Menu

 * @package Soul\Menu
 *
 */
class Builder
{

    protected $links = [];

    /**
     * @param array $menuConfig Configuration of the menu
     * @param bool  $subMenu    Is this menu a submenu
     *
     * @return Menu
     */
    public static function build(array $menuConfig, $subMenu = false)
    {
        $menuObject = new Menu();
        $count = 0;

        foreach ($menuConfig as $name => $item) {

            $firstLevel = false;
            $link = $item;

            // handle sub-menu links properly
            if (is_array($item) && array_key_exists('link', $item)) {
                $link = $item['link'];
                unset($item['link']);
            } elseif (is_array($item)) {
                $link = '#';
            }

            if (!++$count == 0) {
                $firstLevel = true;
            }

            // build the link
            $menuObject->addLink($name, static::buildLink($link, $subMenu, $firstLevel));

            // build additional submenu's
            if (is_array($item)) {
                $menuObject->addSubMenu($name, static::build($item, true));
            }

        }

        return $menuObject;
    }

    /**
     * Builds a menu item
     *
     * @param array $link       Menu item to be build
     * @param bool  $subMenu    Item exists in a submenu
     * @param bool  $firstLevel First level menu item
     *
     * @return array
     */
    public static function buildLink($link, $subMenu = false, $firstLevel = false)
    {

        $classes = array();
        if ($firstLevel && !$subMenu) {
            $classes[] = 'firstLevel';
        }

        if (strpos(Util::getCurrentUrl(), $link) !== false) {
            $classes[] = 'active';
        }

        $parsedLink = [
            'class' => $classes,
            'link' => $link
        ];

        return $parsedLink;
    }

}
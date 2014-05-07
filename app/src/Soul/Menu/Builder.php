<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Menu
 */

namespace Soul\Menu;

use Phalcon\DI;
use Phalcon\Mvc\Url;
use Soul\Auth\AuthService;
use Soul\Menu;
use Soul\Module;
use Soul\Util;

/**
 * Menu generator
 *
 * Class Menu

 * @package Soul\Menu
 *
 */
class Builder extends Module
{

    protected $links = [];

    /**
     * @var null
     */
    protected $breadcrumbs = null;

    /**
     * @param array             $menuConfig Configuration of the menu
     * @param bool              $subMenu    Is this menu a submenu
     *
     * @param array             $originalMenuConfig
     * @param \Soul\Menu|string $menuClass
     *
     * @return Menu
     */
    public static function build(array $menuConfig, $subMenu = false, $originalMenuConfig = array(), $menuClass = 'Menu', $breadcrumbs = null)
    {
        $menuObject = new $menuClass;
        $count = 0;
        $auth = DI::getDefault()->get('auth');

        if (array_key_exists('menu', $menuConfig)) {
            $menu = $menuConfig['menu'];
        } else {
            $menu = $menuConfig;
            $menuConfig = $originalMenuConfig;
        }

        foreach ($menu as $name => $item) {

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

            if (static::isAllowed($menuConfig, $name, $auth)) {

                // build the link
                $menuObject->addLink($name, static::buildLink($link, $subMenu, $firstLevel));
            }

            // build additional submenu's
            if (is_array($item)) {
                $menuObject->addSubMenu($name, static::build($item, true, $menuConfig, $menuClass));
            }

        }

        return $menuObject;
    }

    /**
     * @param array       $menuConfig
     * @param $item
     * @param AuthService $auth
     * @return bool
     */
    public static function isAllowed(array $menuConfig, $item, AuthService $auth)
    {
        $loggedIn = $auth->isLoggedIn();

        if (array_key_exists('guest', $menuConfig) && in_array($item, $menuConfig['guest']) && $loggedIn) {
            return false;
        }

        if (array_key_exists('admin', $menuConfig) && in_array($item, $menuConfig['admin'])) {

            if (!$loggedIn) {
                return false;
            }

            if ($loggedIn && $auth->getAuthData()->getUserType() < \Soul\AclBuilder::ROLE_ADMIN) {
                return false;
            }

        }

        if (array_key_exists('authenticated', $menuConfig) && in_array($item, $menuConfig['authenticated']) && !$loggedIn) {
            return false;
        }


        return true;
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
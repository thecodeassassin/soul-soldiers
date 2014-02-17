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

    protected $links = array();

    protected $menuConfig = array();

    /**
     * Build the Menu object
     *
     * @param array $menuConfig   Menu configuration
     * @param bool  $disableCache Do not use cache
     */
    public function __construct(array $menuConfig, $disableCache = false)
    {
        parent::__construct();
        $this->menuConfig = $menuConfig;

        $cacheKey = crc32(serialize($menuConfig));
        $menu = array();

        if ($this->cache->exists($cacheKey) && !$disableCache) {
            $menu = $this->cache->get($cacheKey);
        }

        $menu = $this->processMenuConfig($menuConfig);

//        die(var_dump($this->dispatcher->getControllerName()));
    }

    /**
     * @param array $menuConfig Configuration of the menu
     *
     * @return array
     */
    protected function processMenuConfig(array $menuConfig)
    {
        $output = [];

        foreach ($menuConfig as $config) {
            if (is_array($config)) {
                $output[] = $this->processMenuConfig($config);
            }


        }

        return $output;
    }

    protected function buildLink($name, $location)
    {

    }

    /**
     * Output the menu as HTML
     *
     * @param Menu $menuObj Menu object to output
     */
    public static function outputHTML(Menu $menuObj)
    {

    }
}
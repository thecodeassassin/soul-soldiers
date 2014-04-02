<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Manager
 */

namespace Soul\Assets;

use Phalcon\Assets\Collection;
use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;
use Phalcon\Cache\BackendInterface;
use Phalcon\Config;
use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Tag;
use Soul\Kernel;

/**
 * Class Manager
 *
 * @package Soul\Assets
 */
class Manager extends \Phalcon\Assets\Manager
{

    /**
     * @var array
     */
    protected $collectionTypes = [];
    /**
     * @var BackendInterface
     */
    protected $cache;

    /**
     * @var Config
     */
    protected $config;


    /**
     * @param \Phalcon\DI|\Phalcon\DiInterface $di          Dependency injector
     * @param \Phalcon\Config                  $assetConfig Configuration
     *
     * @return \Soul\Assets\Manager
     */
    public function __construct(DiInterface $di, Config $assetConfig)
    {
//        $this->cache = $di->get('cache');
        $this->config = $di->get('config');
//
//        $cacheKey = crc32(serialize($assetConfig));
//        // if the collection exists
//        if ($this->cache->exists($cacheKey)) {
//
//            $this->_collections = $this->cache->get($cacheKey);
//
//            return $this;
//        }

        foreach ($assetConfig as $collectionName => $collection) {

            $collectionObject = $this->collection($collectionName);

            foreach ($collection as $type => $assets) {
                $this->collectionTypes[$collectionName] = $type;

                if (!$assets instanceof Config) {
                    continue;
                }

                foreach ($assets as $asset) {
                    $internal = (strpos($asset, '//') === false);

                    if ($type == 'css') {
                        $collectionObject->addCss($asset, $internal, ($internal ? true : false));
                    } else {
                        $collectionObject->addJs($asset, $internal, ($internal ? true : false));
                    }
                }
            }

        }

        // build the collections
        $this->build();

//        $this->cache->save($cacheKey, $this->_collections);

        return $this;
    }

    /**
     * Build the collections
     */
    protected function build()
    {

        foreach ($this->_collections as $name => $collection) {
            $filters = [];
            $type = $this->collectionTypes[$name];

            // do not minify css/js on dev (takes too much time and is not relevant)
            if (APPLICATION_ENV != Kernel::ENV_DEVELOPMENT) {
                if ($type == 'css') {

                    if (APPLICATION_ENV != Kernel::ENV_DEVELOPMENT) {
                        $filters[] = new YuiCompressor();
                    }

                } elseif ($type == 'js') {
                    $filters[] = new Jsmin();
                }
            }

            // create the scripts and add them
            $collection->join(true)
                ->setFilters($filters)
                ->setTargetPath(sprintf('%s/%s.%s', $this->config->application->cacheDir, $name, $type))
                ->setTargetUri(sprintf('static/%s.%s', $name, $type));

        }


    }

    /**
     * Output already existing collections
     *
     * @param null|string $collection
     *
     * @return string|void
     */
    public function outputCss($collection)
    {

        if ($collectionObj = $this->get($collection)) {
            if (APPLICATION_ENV != Kernel::ENV_DEVELOPMENT &&  is_readable($collectionObj->getTargetPath())) {
                return Tag::stylesheetLink([$collectionObj->getTargetUri()]);
            } else {
                parent::outputCss($collection);
            }
        } else {
            parent::outputCss($collection);
        }

    }






}
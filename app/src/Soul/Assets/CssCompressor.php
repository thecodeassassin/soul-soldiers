<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Assets;

use Phalcon\Assets\FilterInterface;

/**
 * Filters CSS content using mrclays's minify library
 *
 * @param string $contents
 * @return string
 */
class CssCompressor implements FilterInterface
{

    protected $options;

    /**
     * CssCompressor constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * Do the filtering
     *
     * @param string $contents
     * @return string
     */
    public function filter($contents)
    {

        // use the mrclay css minifier
        return \Minify_CSS_Compressor::process($contents);
    }
}
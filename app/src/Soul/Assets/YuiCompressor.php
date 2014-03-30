<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Assets;

use Phalcon\Assets\FilterInterface;
use Phalcon\DI;
use Soul\Module;

/**
 * Filters CSS content using mrclays's minify library
 *
 * @param string $contents
 * @return string
 */
class YuiCompressor extends Module implements FilterInterface
{

    protected $options;

    /**
     * CssCompressor constructor
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $options = array_merge([
            'path' => '/usr/bin/yui-compressor',
            'extra-options' => '',
            'type' => 'css'
        ], $options);

        if (!is_executable($options['path'])) {
            throw new \Exception(sprintf('Yui-Compressor not found at %s', $options['path']));
        }

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
        $cacheDir = DI::getDefault()->get('config')->application->cacheDir;
        $tmpFile = $cacheDir.uniqid();
        $tmpFileDest = $cacheDir.uniqid();

        //Write the string contents into a temporal file
        file_put_contents($tmpFile, $contents);

        $cmd = sprintf(
            '%s --type %s %s %s -o %s',
            $this->options['path'],
            $this->options['type'],
            $this->options['extra-options'],
            $tmpFile,
            $tmpFileDest
        );

        system($cmd);

        $output = file_get_contents($tmpFileDest);

        unlink($tmpFile);
        unlink($tmpFileDest);

        //Return the contents of file
        return $output;
    }
}
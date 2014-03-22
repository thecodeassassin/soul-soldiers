<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AbstractData 
 */  

namespace Soul\Payment\Data;


/**
 * Class AbstractData
 *
 * @package Soul\Payment\Data
 */
abstract class AbstractData
{

    /**
     * Parse the data object to array
     *
     * @return \ReflectionProperty[]
     */
    public function toArray()
    {
        $properties = [];

        $reflection = new \ReflectionClass($this);
        $props = $reflection->getProperties();

        foreach ($props as $property) {
            $name = $property->getName();

            if (isset($this->$name)) {
                $properties[$name] = $this->$name;
            }

        }

        return $properties;
    }

    /**
     * @param $baseUrl
     * @return string
     */
    public function toUri($baseUrl)
    {

        $params = http_build_query($this->toArray(), null, '&', PHP_QUERY_RFC3986);

        return sprintf('%s?%s', $baseUrl, $params);
    }
} 
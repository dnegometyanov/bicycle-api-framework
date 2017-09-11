<?php

namespace Bicycle\Core;

/**
 * Core application class
 *
 * Class Application
 * @package Bicycle\Core
 */
class Container
{
    /**
     * @var array
     */
    protected $container;

    function __construct()
    {
        return $this;
    }

    /**
     * Injects a dependency to container.
     *
     * @param $key
     * @param $instance
     * @return Container
     */
    public function register($key, $instance): Container
    {
        $this->container[$key] = $instance;

        return $this;
    }

    public function get($key)
    {
        if (!isset($this->container[$key])) {
            throw new \InvalidArgumentException(sprintf('Object with key %s not founded in container', $key));
        }

        return $this->container[$key];
    }
}
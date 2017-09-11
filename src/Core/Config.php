<?php

namespace Bicycle\Core;

/**
 * Core controller class
 *
 * Class Config
 * @package Bicycle\Core
 */
class Config
{
    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $configJson = \file_get_contents(ROOT_PATH . '/resources/config/config.json');
        $this->config = json_decode($configJson, true);
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
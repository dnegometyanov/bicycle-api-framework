<?php

namespace Bicycle\Core;

class Route
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $handler;

    /**
     * @var array
     */
    protected $uriParams;

    public function __construct(string $method, string $pattern, string $handler)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * @param array $uriParams
     */
    public function setUriParams(array $uriParams): void
    {
        $this->uriParams = $uriParams;
    }

    /**
     * @return array
     */
    public function getUriParams()
    {
        return $this->uriParams;
    }
}
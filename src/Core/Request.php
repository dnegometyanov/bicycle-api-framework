<?php

namespace Bicycle\Core;

class Request
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $headers;

    /**
     * @var string
     */
    protected $body;

    /**
     * @return string
     */
    function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    function getBody()
    {
        return $this->body;
    }

    /**
     * @return Request
     */
    public function initFromGlobals(): Request
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->uri = $_SERVER['REQUEST_URI'];

        $this->headers = $this->getAllHeaders();

        $this->body = \file_get_contents('php://input');

        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    public function getHeader($key):? string
    {
        return $this->headers[$key] ?: null;
    }

    protected function getAllHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headerKey = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerKey] = $value;
            }
        }
        return $headers;
    }
}
<?php

namespace Bicycle\Core;

/**
 * Class Router
 *
 * @method Router get(string $pattern, string $handler)
 * @method Router post(string $pattern, string $handler)
 * @method Router put(string $pattern, string $handler)
 * @method Router delete(string $pattern, string $handler)
 *
 * @package Bicycle\Core
 */
class Router
{
    const AVAILABLE_METHODS = ['get', 'post', 'put', 'delete'];

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, self::AVAILABLE_METHODS)) {
            throw new \InvalidArgumentException(sprintf('Method %s is not allowed', $name));
        }

        $pattern = $arguments[0];
        $handler = $arguments[1];

        $this->routes[] = new Route($name, $pattern, $handler);

        return $this;
    }

    /**
     * Returns Route, founded for request
     *
     * @param Request $request
     * @return Route|null
     */
    public function match(Request $request):? Route
    {
        $requestMethod = strtolower($request->getMethod());
        $requestUri = $request->getUri();

        foreach ($this->routes as $route) {
            /** @var Route $route */
            if (
                $route->getMethod() == $requestMethod &&
                $uriParamsValues = $this->uriMatchPattern($requestUri, $route->getPattern())
            ) {
                $uriParamsNames = $this->getUriParamNames($route->getPattern());
                if ($uriParamsNames) {
                    $uriParams = array_combine($uriParamsNames, $uriParamsValues);
                    $route->setUriParams($uriParams);
                }

                return $route;
            }
        }

        return null;
    }

    /**
     * Returns parameters from Uri by Route pattern
     *
     * @param $requestUri
     * @param $pattern
     * @return array|null
     */
    protected function uriMatchPattern($requestUri, $pattern):? array
    {
        $match = preg_match($this->createRegexpFromPattern($pattern), $requestUri, $uriParamsValuesArray);

        if ($match > 0) {
            array_shift($uriParamsValuesArray);

            return $uriParamsValuesArray;
        }

        return null;
    }

    /**
     * Creates regexp from route pattern
     *
     * @param $pattern
     * @return mixed
     */
    protected function createRegexpFromPattern($pattern)
    {
        return '~' . preg_replace('/{[^}]+}/', '([^/]+)', $pattern) . '~';
    }

    /**
     * Get uri params names array from route pattern
     * i.e for /transaction/email/amount/ will return ['email', 'amount']
     *
     * @param $pattern
     * @return mixed
     */
    protected function getUriParamNames($pattern)
    {
        preg_match_all('/{[^}]+}/', $pattern, $resultMatchUriParamsNames);

        $uriParamsNames = [];
        foreach ($resultMatchUriParamsNames[0] as $key => $resultMatchUriParamsName) {
            $uriParamsNames[] = preg_replace(['/{/', '/}/'], ['', ''], $resultMatchUriParamsName);
        }

        return $uriParamsNames;
    }
}
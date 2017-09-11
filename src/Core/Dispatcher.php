<?php

namespace Bicycle\Core;

use Bicycle\Core\Exception\NotFoundMethodException;

/**
 * Core application class
 *
 * Class Dispatcher
 * @package Bicycle\Core
 */
class Dispatcher
{
    const HANDLER_DELIMITER = ':';

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Route $route
     * @return Dispatcher
     */
    public function setRoute(Route $route): Dispatcher
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }


    /**
     * @param Container $container
     * @return Dispatcher
     */
    public function setContainer(Container $container): Dispatcher
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Calls corresponding method for route from service in the container
     *
     * @param Route $route
     * @param Container $container
     * @param Request $request
     * @return mixed
     * @throws \Exception
     * @internal param array $args
     * @internal param Request|null $request Request to process
     */
    function handle(Route $route, Container $container, Request $request)
    {
        $handler = $route->getHandler();
        list ($containerKey, $method) = explode(self::HANDLER_DELIMITER, $handler);

        $handlerObject = $container->get($containerKey);

        if (!method_exists($handlerObject, $method)) {
            throw new NotFoundMethodException(sprintf('Method %s does not exists in object of class %s.', $method, get_class($handlerObject)));
        }

        return call_user_func_array([$handlerObject, $method], [$request, $route]);
    }

}
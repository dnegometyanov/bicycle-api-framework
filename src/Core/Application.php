<?php

namespace Bicycle\Core;

use Bicycle\Core\Exception\AuthorizationException;
use Bicycle\Core\Exception\NotFoundHttpException;

/**
 * Core application class
 *
 * Class Application
 * @package Bicycle\Core
 */
class Application
{
    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_STATUS_SERVER_ERROR = 500;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param Container $container
     * @return Application
     */
    public function setContainer(Container $container): Application
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
     * @param Router $router
     * @return Application
     */
    public function setRouter(Router $router): Application
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher $dispatcher
     * @return Application
     */
    public function setDispatcher(Dispatcher $dispatcher): Application
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Handles the request and delivers the response.
     *
     * @param Request|null $request Request to process
     * @throws \Exception
     */
    public function handle(Request $request = null)
    {
        if (null === $request) {
            $request = new Request();
            $request->initFromGlobals();
        }

        /** @var Route $route */
        $route = $this->router->match($request);

        if (!$route) {
            throw new NotFoundHttpException('No matching route founded for request.');
        }

        $response = $this->dispatcher->handle($route, $this->container, $request);

        if (isset($response['status'])) {
            $status = $response['status'];
        } else {
            $status = 200;
            $response['status'] = $status;
        }

        $this->sendResponse(json_encode($response), ['Content-Type: application/json'], $status);
    }

    /**
     * @param \Exception $e
     * @return array|string
     */
    public function createExceptionResponse(\Exception $e): array
    {
        $status = self::HTTP_STATUS_SERVER_ERROR;
        if ($e instanceof NotFoundHttpException) {
            $status = self::HTTP_STATUS_NOT_FOUND;
        } else if ($e instanceof AuthorizationException) {
            $status = self::HTTP_STATUS_UNAUTHORIZED;
        }

        return [
            'status' => $status,
            'errorMessages' => [$e->getMessage()]
        ];
    }

    /**
     * Sends response
     *
     * @param $responseBody
     * @param array $headers
     * @param int $status
     * @internal param string $body
     */
    public function sendResponse($responseBody, array $headers = [], $status = 200)
    {
        foreach ($headers as $header) {
            header($header);
        }

        http_response_code($status);
        echo $responseBody;
    }
}
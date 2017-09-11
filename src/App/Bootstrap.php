<?php

namespace Bicycle\App;

use Bicycle\Core\Application;
use Bicycle\Core\Config;
use Bicycle\Core\Container;
use Bicycle\Core\Dispatcher;
use Bicycle\Core\Request;
use Bicycle\Core\Router;

class Bootstrap
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap(): void
    {
        $this->app
            ->setContainer(new Container())
            ->setRouter(new Router())
            ->setDispatcher(new Dispatcher());

        $this->app->getContainer()->register(
            'app.config',
            new Config()
        );

        // Load services (before loading routes !)
        $servicesLoader = new ServicesLoader($this->app);
        $servicesLoader->bindServicesIntoContainer();

        // Load routes
        $routesLoader = new RoutesLoader($this->app);
        $routesLoader->bindRoutesToControllers();
    }
}
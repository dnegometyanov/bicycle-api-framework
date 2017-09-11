<?php

namespace Bicycle\App;

use Bicycle\App\Controller\TransactionController;
use Bicycle\Core;

class RoutesLoader
{
    private $app;

    public function __construct(Core\Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    private function instantiateControllers(): void
    {
        /** @var Config $configObj */
        $configObj = $this->app->getContainer()->get('app.config');
        /** @var array $config */
        $config = $configObj->getConfig();

        $this->app->getContainer()->register(
            'controller.transaction',
            new TransactionController(
                $this->app->getContainer()->get('repository.transaction'),
                $this->app->getContainer()->get('service.transaction'),
                $this->app->getContainer()->get('entity.validator'),
                $config['auth.basic.token']
            )
        );
    }

    public function bindRoutesToControllers(): void
    {
        $this->app->getRouter()->post('/transaction/{email}', "controller.transaction:processTransaction");
    }
}


<?php

namespace Bicycle\App;

use Bicycle\App\Repository\TransactionRepository;
use Bicycle\App\Service\TransactionService;
use Bicycle\Core\Application;
use Bicycle\Core\Config;
use Bicycle\Core\EntityValidator;

class ServicesLoader
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer(): void
    {
        $this->app->getContainer()->register(
            'entity.validator',
            new EntityValidator()
        );

        /** @var Config $configObj */
        $configObj = $this->app->getContainer()->get('app.config');
        /** @var array $config */
        $config = $configObj->getConfig();

        /**
         * @var \PDO
         */
        $pdo = new \PDO(
            sprintf('mysql:host=%s;dbname=%s', $config['db.mysql']['host'], $config['db.mysql']['db']),
            $config['db.mysql']['user'],
            $config['db.mysql']['password'],
            [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );

        $this->app->getContainer()->register(
            'repository.transaction',
            new TransactionRepository($pdo)
        );

        $this->app->getContainer()->register(
            'service.transaction',
            new TransactionService()
        );
    }
}


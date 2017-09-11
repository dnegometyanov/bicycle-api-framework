<?php

namespace Bicycle\Core;

abstract class AbstractPDOEntityRepository
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract function getEntityClass(): string;

    abstract function getTableName(): string;

    /**
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $stmt = $this->pdo->prepare(sprintf('SELECT * FROM %s', $this->getTableName()));
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }
}
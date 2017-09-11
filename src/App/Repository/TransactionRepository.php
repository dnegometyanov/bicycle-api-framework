<?php

namespace Bicycle\App\Repository;

use Bicycle\App\Entity\Transaction;
use Bicycle\Core\AbstractPDOEntityRepository;

class TransactionRepository extends AbstractPDOEntityRepository
{
    public function getEntityClass(): string
    {
        return Transaction::class;
    }

    public function getTableName(): string
    {
        return 'transaction';
    }

    /**
     * Saves Transaction to DB
     *
     * @param Transaction $transaction
     * @return Transaction
     */
    public function save(Transaction $transaction): Transaction
    {
        if (!$transaction->getId()) {
            $stmt = $this->pdo->prepare(
                sprintf(
                    'INSERT INTO %s (email, amount, status, create_date) 
                     VALUES (:email, :amount, :status, :create_date)',
                    $this->getTableName()
                )
            );
        } else {
            $stmt = $this->pdo->prepare(
                sprintf(
                    'UPDATE %s
                 SET email = :email, amount = :amount, status = :status, create_date = :create_date 
                 WHERE id = :id',
                    $this->getTableName())

            );
            $stmt->bindParam(':id', $transaction->getid(), \PDO::PARAM_INT);
        }

        $stmt->bindParam(':email', $transaction->getEmail(), \PDO::PARAM_STR);
        $stmt->bindParam(':amount', $transaction->getAmount(), \PDO::PARAM_STR);
        $stmt->bindParam(':status', $transaction->getStatus(), \PDO::PARAM_STR);
        $stmt->bindParam(':create_date', $transaction->getCreateDate(), \PDO::PARAM_STR);

        $stmt->execute();
        $id = $this->pdo->lastInsertId();

        $transaction->setId((integer)$id);

        return $transaction;
    }

}
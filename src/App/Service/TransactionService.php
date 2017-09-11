<?php

namespace Bicycle\App\Service;

use Bicycle\App\Entity\Transaction;

class TransactionService
{
    public function processTransaction(Transaction $transaction): void
    {
        // Do some transaction process actions here.
        $transactionStatus = Transaction::getAvailableStatuses()[rand(0, 1)];
        $transaction->setStatus($transactionStatus);
        $transaction->setCreateDate(date('Y-m-d h:i:s'));
    }
}
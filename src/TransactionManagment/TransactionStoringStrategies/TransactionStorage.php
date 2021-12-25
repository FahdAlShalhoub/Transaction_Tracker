<?php

namespace TransactionManager;

class TransactionStorage
{
    private $storageStrategies;

    public function __construct()
    {
        $this->storageStrategies = [
            new YnabApiTransactionStoringStrategy($_ENV["YNAB_TOKEN"])
        ];
    }

    public function storeTransactions(array $transactions)
    {
        foreach ($this->storageStrategies as $strategy)
        {
            $strategy->storeTransactions($transactions);
        }
    }
}
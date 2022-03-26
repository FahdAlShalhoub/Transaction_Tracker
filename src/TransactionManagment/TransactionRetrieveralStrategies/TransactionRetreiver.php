<?php

namespace TransactionManager;

class TransactionRetreiver
{
    private $transactionRetrievalStrategies;

    public function __construct()
    {
        $this->transactionRetrievalStrategies = [
            AlinmaGmailStrategy::getInstance()
        ];
    }

    public function getTransactions(): array
    {
        $transactions = [];
        foreach ($this->transactionRetrievalStrategies as $transactionRetrievalStrategy) {
            $transactions = array_merge($transactions, $transactionRetrievalStrategy->retreiveTransactions());
        }
        return $transactions;
    }

}
<?php

namespace TransactionManager;

class TransactionRetreiver
{
    private $transactionRetreivalStrategy;

    public function __construct(TransactionRetreivalStrategy $transactionRetreivalStrategy = null)
    {
        if($transactionRetreivalStrategy == null){
            $transactionRetreivalStrategy = GmailStrategy::getInstance();
        }

        $this->transactionRetreivalStrategy = $transactionRetreivalStrategy;
    }

    public function getTransactions()
    {
        $transactions = $this->transactionRetreivalStrategy->retreiveTransactions();
        $transactionsJsons = [];
        
        foreach($transactions as $transaction){
            array_push($transactionsJsons, json_encode($transaction));
        }

        return $transactionsJsons;
    }
}
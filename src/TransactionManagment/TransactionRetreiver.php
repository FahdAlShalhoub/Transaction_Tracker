<?php

namespace TransactionManager;

class TransactionRetreiver
{
    private $transactionRetreivalStrategy;

    public function __construct(TransactionRetreivalStrategy $transactionRetreivalStrategy = null)
    {
        if($transactionRetreivalStrategy == null){
            $transactionRetreivalStrategy = AlinmaGmailStrategy::getInstance();
        }

        $this->transactionRetreivalStrategy = $transactionRetreivalStrategy;
    }

    public function getTransactions()
    {
        return $this->transactionRetreivalStrategy->retreiveTransactions();
    }

}
<?php

namespace TransactionManager;

interface ITransactionStoringStrategy
{
    public function storeTransactions(array $transactions);
}
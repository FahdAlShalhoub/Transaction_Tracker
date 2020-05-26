<?php

namespace TransactionManager;

interface TransactionRetreivalStrategy 
{
    public function retreiveTransactions(): array; 
}
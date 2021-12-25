<?php

namespace TransactionManager;

use GuzzleHttp\Client;

abstract class ApiTransactionStoringStrategy implements ITransactionStoringStrategy
{
    private $client;

    public function __construct()
    {
        $this->client =  new Client();
    }

    public function storeTransactions(array $transactions)
    {
         foreach($transactions as $transaction)
         {
             $this->client->request($this->getHttpMethod(), $this->getUrl(), [
                 "headers" => $this->getHeaders(),
                 "json" => $this->getBody($transaction)
             ]);


             print("\n-------------------------------------------------\n");
             print("\n" . $transaction->vendor . "\n");
             print("\n" . $transaction->amount . " " . $transaction->currency . "\n");
             print("\n" . $transaction->timestamp->format("Y-m-d H:i:s") . "\n");
             print("\n-------------------------------------------------\n");

         }
     }


    protected abstract function getUrl(): string;

    protected abstract function getHttpMethod(): string;

    protected abstract function getBody(Transaction $transaction): array;

    protected abstract function getHeaders(): array;
}
<?php
require realpath('./vendor/autoload.php');

use TransactionManager\TransactionRetreiver;
use CurrencyConverter\CurrconvCurrencyConverter;
use TransactionManager\TransactionStorage;

$dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
$dotenv->load();

print("\n Retrieving Transactions....\n");
$transactionRetriever = new TransactionRetreiver();
$transactions = $transactionRetriever->getTransactions();
print("\n Retrieved Transactions Successfully\n");

$CurrConvAPIKey = $_ENV["CURRENCY_CONVERTER_API_KEY"];
$currencyConverter = new CurrconvCurrencyConverter($CurrConvAPIKey);

foreach ($transactions as $transaction) {
    if ($transaction->getCurrency() != "SAR") {
        $transaction->amount = $currencyConverter->convertCurrency($transaction->getCurrency(), "SAR", $transaction->getAmount());
        $transaction->currency = "SAR";
    }
}

print("\n Starting Transaction Recording....\n");
$transactionStorage = new TransactionStorage();
$transactionStorage->storeTransactions($transactions);
print("\n Number Of Transactions Processed: " . sizeOf($transactions) . "\n");
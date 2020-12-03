<?php 
require realpath('./vendor/autoload.php');

use TransactionManager\TransactionRetreiver;

$dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
$dotenv->load();

$transcationRetreiver = new TransactionRetreiver();
$transactions = $transcationRetreiver->getTransactions();

$client = new GuzzleHttp\Client();
$YNABToken = $_ENV["YNAB_TOKEN"];

print("Starting Transaction Recording....\n");
foreach($transactions as $transaction){
    $res = $client->request("POST", "https://api.youneedabudget.com/v1/budgets/last-used/transactions", [
        "headers" => [
            "Authorization" => "Bearer " . $YNABToken,
            "Content-Type" => "application/json"
        ],
        "json" => [
            "transaction" => [
                "account_id" => "1de95c0e-bcf4-4733-80f3-8a500900d326",
                "date" => $transaction->timestamp->format("Y-m-d\TH:i:s.u"),
                "amount" => "-" . ($transaction->amount * 1000),
                "payee_name" => $transaction->vendor,
                "approved" => false,
                "memo" => "This is an auto-generated transaction",
                "category_id" => "bd098abf-2378-48ee-9b9f-fc39d6a1e357"
            ]
        ]
    ]);

    print("\n");
    print("-------------------------------------------------\n");
    print($transaction->vendor . "\n");
    print($transaction->amount . " SAR" . "\n");
    print($transaction->timestamp->format("Y-m-d H:i:s") . "\n");
    print("-------------------------------------------------");
    print("\n");
}

print("Number Of Transactions Processed: " . sizeOf($transactions));
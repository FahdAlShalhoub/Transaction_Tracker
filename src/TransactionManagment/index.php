<?php 
require realpath('./vendor/autoload.php');

use TransactionManager\TransactionRetreiver;

$dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
$dotenv->load();

print("\n Retreiving Transactions....\n");
$transcationRetreiver = new TransactionRetreiver();
$transactions = $transcationRetreiver->getTransactions();
print("\n Retreived Transactions Successfully\n");

$client = new GuzzleHttp\Client();
$YNABToken = $_ENV["YNAB_TOKEN"];

print("\n Starting Transaction Recording....\n");
foreach($transactions as $transaction){
    $res = $client->request("POST", "https://api.youneedabudget.com/v1/budgets/d841696e-621c-4df9-a99d-55725db3cf39/transactions", [
        "headers" => [
            "Authorization" => "Bearer " . $YNABToken,
            "Content-Type" => "application/json"
        ],
        "json" => [
            "transaction" => [
                "account_id" => "8ee1b41d-e1a2-4698-8776-b51100aeeee9",
                "date" => $transaction->timestamp->format("Y-m-d\TH:i:s.u"),
                "amount" => "-" . ($transaction->amount * 1000),
                "payee_name" => $transaction->vendor,
                "approved" => false,
                "memo" => "This is an auto-generated transaction",
                "category_id" => "bd098abf-2378-48ee-9b9f-fc39d6a1e357"
            ]
        ]
    ]);

    
    print("\n-------------------------------------------------\n");
    print("\n" . $transaction->vendor . "\n");
    print("\n" . $transaction->amount . " SAR" . "\n");
    print("\n" . $transaction->timestamp->format("Y-m-d H:i:s") . "\n");
    print("\n-------------------------------------------------\n");
    
}

print("\n Number Of Transactions Processed: " . sizeOf($transactions) . "\n");
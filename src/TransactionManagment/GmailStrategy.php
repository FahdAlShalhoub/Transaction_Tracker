<?php

namespace TransactionManager;

use TransactionManager\Transaction;

abstract class GmailStrategy implements TransactionRetreivalStrategy
{
    protected $gmailService;
    private static $instance;

    private function __construct($tokenPath = "./token.json")
    {
        $client = new \Google_Client();
        $client->setApplicationName('Transaction_Tracker');
        $client->setScopes(\Google_Service_Gmail::GMAIL_READONLY);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $token = file_get_contents($tokenPath);
        
        $client->setAccessToken($token);

        if($client->isAccessTokenExpired()){
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }

        $this->gmailService = new \Google_Service_Gmail($client);
    }

    public static function getInstance()
    {
        $class = get_called_class();
        if (!self::$instance[$class])
        {
            self::$instance[$class] = new static();
        }
        return self::$instance[$class];
    }

    public function retreiveTransactions(): array
    {
        $transactions = array(); 
        $response = $this->getEmailMessages();

        foreach($response as $message){
            $b64Url = $this->gmailService->users_messages->get("me",$message->getId(),["format" => "full"])->getPayload()->getBody()->getData();
            $b64 = strtr($b64Url, '-_', '+/'); 
            $messageSnippet = base64_decode($b64);
            
            $amount = $this->extractAmountFromEmail($messageSnippet);
            $timestamp = $this->extractTimestampFromEmail($messageSnippet);
            $vendor = $this->extractVendorFromEmail($messageSnippet);
            $cardNumber = $this->extractCardNumberFromEmail($messageSnippet);

            $transaction = new Transaction($amount,$timestamp,$vendor,$cardNumber);

            array_push($transactions,$transaction);
        }

        return $transactions;
    }

    abstract protected function getEmailMessages();

    //TODO: Convert Foregin Currencies To Saudi Riyals
    abstract protected function extractAmountFromEmail($email): float;

    abstract protected function extractTimestampFromEmail($email): \DateTime;

    abstract protected function extractVendorFromEmail($email): string;

    abstract protected function extractCardNumberFromEmail($email): int;
}
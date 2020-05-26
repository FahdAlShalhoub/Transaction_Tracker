<?php

namespace TransactionManager;

use TransactionManager\Transaction;

class GmailStrategy implements TransactionRetreivalStrategy
{
    private $gmailService;
    private static $gmailStrategy = null;

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

    public static function getInstance(): GmailStrategy
    {
        if(self::$gmailStrategy == null){
            self::$gmailStrategy = new GmailStrategy();
        }

        return self::$gmailStrategy;
    }

    public function retreiveTransactions(): array
    {
        $transactions = array(); 
        $response = $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com subject:(POS purchase) AND NOT subject:(International POS purchase) "])->getMessages();

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

    //TODO: Convert Foregin Currencies To Saudi Riyals
    private function extractAmountFromEmail($email): float
    {
        preg_match('/(?:SAR|S\.?R|RIYAL|DOLLAR)\s\d*(?:\.\d*)?/',$email,$amountWithCurrency);
        $amount = preg_split('/\s/',$amountWithCurrency[0]);
        
        return (float) $amount[1];
    }

    private function extractTimestampFromEmail($email): \DateTime
    {
        preg_match('/\d*(?:th|nd|st|rd),\s\w*\s\d*/',$email,$timestamp);

        if($timestamp){
            preg_match('/\d*(?:th|nd|st|rd)/',$timestamp[0],$day);
            preg_match('/(?<=\s)\w*(?=\s)/',$timestamp[0],$month);
            preg_match('/\d{4}/',$timestamp[0],$year);

            $timestamp = $month[0]." ".$day[0].", ".$year[0];
            $dateTime = new \DateTime($timestamp);
        } else {
            $dateTime = new \DateTime('NOW');
        }

        return $dateTime;
    }

    private function extractVendorFromEmail($email): string
    {
        preg_match('/(?<=<td>)(?:[a-zA-Z][\s-]?)+/',$email,$vendor);
        return $vendor[0];
    }

    private function extractCardNumberFromEmail($email): int
    {
        preg_match('/\d{4}(?=\*)/',$email,$cardNumber);
        return (int) $cardNumber[0];
    }
}
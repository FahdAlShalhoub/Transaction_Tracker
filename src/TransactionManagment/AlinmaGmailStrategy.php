<?php

namespace TransactionManager;

class AlinmaGmailStrategy extends GmailStrategy
{
    protected function getEmailMessages()
    {
        $currentDate = new \DateTime("NOW");
        $timeInEpochBeforeAnHour = $currentDate->sub(new \DateInterval("PT1H"))->getTimestamp();
        return $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com subject:(POS purchase mada atheer) AND NOT subject:(International POS purchase) after:{$timeInEpochBeforeAnHour}"])->getMessages();
    }

    protected function extractAmountFromEmail($email): float
    {
        $doc = new \DOMDocument();
        $doc->loadHTML($email);
        $amountWithCurrency = trim($doc->getElementsByTagName("table")->item(10)->nodeValue);
        $amount = preg_split('/\s/',$amountWithCurrency);
        return (float) $amount[0];
    }

    protected function extractTimestampFromEmail($email): \DateTime
    {
        preg_match('/\d{4}-\d{2}-\d{2}/',$email,$date);
        preg_match('/\d{2}:\d{2}/',$email,$time);

        if($date){
            $dateTime = new \DateTime($date[0].$time[0]);
        } else {
            $dateTime = new \DateTime('NOW');
        }

        return $dateTime;
    }

    protected function extractVendorFromEmail($email): string
    {
        $doc = new \DOMDocument();
        $doc->loadHTML($email);
        $vendor = $doc->getElementsByTagName("table")->item(19)->nodeValue;
        return trim($vendor);
    }

    protected function extractCardNumberFromEmail($email): int
    {
        preg_match('/\d{4}(?=\*)/',$email,$cardNumber);
        return (int) $cardNumber[0];
    } 
}
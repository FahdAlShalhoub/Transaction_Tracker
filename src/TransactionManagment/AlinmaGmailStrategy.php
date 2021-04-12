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

    protected function extractAmountFromEmail($emailDOM): float
    {
        $amountWithCurrency = trim($emailDOM->getElementsByTagName("table")->item(10)->nodeValue);
        $amount = preg_split('/\s/',$amountWithCurrency);
        return (float) $amount[0];
    }

    protected function extractTimestampFromEmail($emailDOM): \DateTime
    {
        $date = trim($emailDOM->getElementsByTagName("table")->item(22)->nodeValue);
        $time = trim($emailDOM->getElementsByTagName("table")->item(25)->nodeValue);

        if($date){
            $dateTime = new \DateTime($date. " ". $time);
        } else {
            $dateTime = new \DateTime('NOW');
        }

        return $dateTime;
    }

    protected function extractVendorFromEmail($emailDOM): string
    {
        $vendor = $emailDOM->getElementsByTagName("table")->item(19)->nodeValue;
        return trim($vendor);
    }

    protected function extractCardNumberFromEmail($emailDOM): int
    {
        $cardNumber = trim($emailDOM->getElementsByTagName("table")->item(13)->nodeValue);
        return (int) $cardNumber;
    } 
}
<?php

namespace TransactionManager;

class AlinmaGmailStrategy extends GmailStrategy
{
    protected function getEmailMessages()
    {
        return $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com subject:(Purchase) label:unread"])->getMessages();
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
        $allTables = $emailDOM->getElementsByTagName("table");
        $vendor = "";

        for ($i = 0; $i < $allTables->length; $i++) {
            if (trim($allTables->item($i)->nodeValue) == "Merchant Name") {
               $vendor = trim($allTables->item($i - 1)->nodeValue);
            }
        }

        return trim($vendor);
    }

    protected function extractCardNumberFromEmail($emailDOM): int
    {
        $cardNumber = trim($emailDOM->getElementsByTagName("table")->item(13)->nodeValue);
        return (int) $cardNumber;
    }

    protected function extractCurrencyFromEmail($emailDOM): string
    {
        $amountWithCurrency = trim($emailDOM->getElementsByTagName("table")->item(10)->nodeValue);
        $amount = preg_split('/\s/',$amountWithCurrency);
        return $amount[1];
    }
}
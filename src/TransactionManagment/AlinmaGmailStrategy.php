<?php

namespace TransactionManager;

class AlinmaGmailStrategy extends GmailStrategy
{
    protected function getEmailMessages()
    {
        $currentDate = new \DateTime("NOW");
        $timeInEpochBeforeAnHour = $currentDate->sub(new \DateInterval("PT1H"))->getTimestamp();
        return $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com subject:(POS purchase) AND NOT subject:(International POS purchase) after:{$timeInEpochBeforeAnHour}"])->getMessages();
    }

    protected function extractAmountFromEmail($email): float
    {
        preg_match('/(?:SAR|S\.?R|RIYAL|DOLLAR)\s\d*(?:\.\d*)?/',$email,$amountWithCurrency);
        $amount = preg_split('/\s/',$amountWithCurrency[0]);
        
        return (float) $amount[1];
    }

    protected function extractTimestampFromEmail($email): \DateTime
    {
        preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}/',$email,$timestamp);

        if($timestamp){
            $dateTime = new \DateTime($timestamp[0]);
        } else {
            $dateTime = new \DateTime('NOW');
        }

        return $dateTime;
    }

    protected function extractVendorFromEmail($email): string
    {
        preg_match('/(?<=<td>)[a-zA-Z\s\.-]+(?=<\/td>)/',$email,$vendor);
        return sizeof($vendor) >= 1 ? $vendor[0]: "UNKNOWN VENDOR";
    }

    protected function extractCardNumberFromEmail($email): int
    {
        preg_match('/\d{4}(?=\*)/',$email,$cardNumber);
        return (int) $cardNumber[0];
    } 
}
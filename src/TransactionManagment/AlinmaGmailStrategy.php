<?php

namespace TransactionManager;

class AlinmaGmailStrategy extends GmailStrategy
{
    protected function getEmailMessages()
    {
        return $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com subject:(POS purchase) AND NOT subject:(International POS purchase) "])->getMessages();
    }

    protected function extractAmountFromEmail($email): float
    {
        preg_match('/(?:SAR|S\.?R|RIYAL|DOLLAR)\s\d*(?:\.\d*)?/',$email,$amountWithCurrency);
        $amount = preg_split('/\s/',$amountWithCurrency[0]);
        
        return (float) $amount[1];
    }

    protected function extractTimestampFromEmail($email): \DateTime
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

    protected function extractVendorFromEmail($email): string
    {
        preg_match('/(?<=<td>)(?:[a-zA-Z][\s-]?)+/',$email,$vendor);
        return $vendor[0];
    }

    protected function extractCardNumberFromEmail($email): int
    {
        preg_match('/\d{4}(?=\*)/',$email,$cardNumber);
        return (int) $cardNumber[0];
    } 
}
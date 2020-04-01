<?php

namespace TransactionManager;

use JsonSerializable;
use DateTime;

class Transaction implements JsonSerializable
{
    private $amount;
    private $timestamp;
    private $vendor;
    private $cardNumber;

    public function __construct($amount,DateTime $timestamp, $vendor, $cardNumber)
    {
        if(!is_double($amount)){
            throw new Exception("Incorrect Data Type: $amount Must Be A double");
        }
        
        if(!is_string($vendor)) {
            throw new Exception("Incorrect Data Type: $vendor Must Be A string");
        }

        if(!is_int($cardNumber)) {
            throw new Exception("Incorrect Data Type: $cardNumber Must Be An int");
        }

        $this->amount = $amount;
        $this->timestamp = $timestamp;
        $this->vendor = $vendor;
        $this->cardNumber = $cardNumber; 
    }

    public function JsonSerialize()
    {
        return [
            'amount' => $amount,
            'timetsamp' => $timestamp,
            'vendor' => $vendor,
            'card_number' => $cardNumber 
        ];
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

}
<?php

namespace TransactionManager;

use JsonSerializable;
use DateTime;

//TODO: Write TestSuite
class Transaction implements JsonSerializable
{
    public $amount;
    public $currency;
    public $timestamp;
    public $vendor;
    public $cardNumber;

    public function __construct($amount, $currency, DateTime $timestamp, $vendor, $cardNumber)
    {
        if(!is_double($amount)){
            throw new \Exception("Incorrect Data Type: $amount Must Be A double");
        }
        
        if(!is_string($vendor)) {
            throw new \Exception("Incorrect Data Type: $vendor Must Be A string");
        }

        if(!is_string($currency)) {
            throw new \Exception("Incorrect Data Type: $currency Must Be A string");
        }

        if(!is_int($cardNumber)) {
            throw new \Exception("Incorrect Data Type: $cardNumber Must Be An int");
        }

        $this->amount = $amount;
        $this->currency = $currency;
        $this->timestamp = $timestamp;
        $this->vendor = $vendor;
        $this->cardNumber = $cardNumber; 
    }

    public function JsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'timestamp' => $this->timestamp,
            'vendor' => $this->vendor,
            'card_number' => $this->cardNumber
        ];
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
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
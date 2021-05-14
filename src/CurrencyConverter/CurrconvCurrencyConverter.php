<?php

namespace CurrencyConverter;

class CurrconvCurrencyConverter implements ICurrencyConverter
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convertCurrency($fromCurrency, $toCurrency, $amount): float
    {
        $access_key = $this->apiKey;

        $ch = curl_init("https://free.currconv.com/api/v7/convert?q=".$fromCurrency."_".$toCurrency."&compact=ultra&apiKey=".$access_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);
        curl_close($ch);

        $exchangeRate = json_decode($json, true);

        return round($amount * reset($exchangeRate), 2);
    }
}
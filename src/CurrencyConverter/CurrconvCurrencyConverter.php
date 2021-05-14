<?php

namespace CurrencyConverter;

use GuzzleHttp\Client;

class CurrconvCurrencyConverter implements ICurrencyConverter
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convertCurrency($fromCurrency, $toCurrency, $amount): float
    {
        $client = new Client();

        $res = $client->request("GET", "https://free.currconv.com/api/v7/convert?q=".$fromCurrency."_".$toCurrency."&compact=ultra&apiKey=".$this->apiKey);
        $exchangeRate = json_decode($res->getBody(), true);

        return round($amount * reset($exchangeRate), 2);
    }
}
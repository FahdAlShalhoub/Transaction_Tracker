<?php

use PHPUnit\Framework\TestCase;
use CurrencyConverter\CurrconvCurrencyConverter;

final class CurrencyConverterTest extends TestCase
{
    public function testCurrencyConversion1()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
        $dotenv->load();
        $apiKey = $_ENV["CURRENCY_CONVERTER_API_KEY"];

        $currencyConverter = new CurrconvCurrencyConverter($apiKey);
        $result = $currencyConverter->convertCurrency("USD", "SAR", 100);
        $this->assertEquals(375.00, $result);
    }

    public function testCurrencyConversion2()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
        $dotenv->load();
        $apiKey = $_ENV["CURRENCY_CONVERTER_API_KEY"];

        $currencyConverter = new CurrconvCurrencyConverter($apiKey);
        $result = $currencyConverter->convertCurrency("EUR", "SAR", 100);
        $this->assertEquals(455.26, $result);
    }
}
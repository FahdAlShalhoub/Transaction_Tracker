<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use CurrencyConverter\CurrconvCurrencyConverter;

final class CurrencyConverterTest extends TestCase
{

    /**
     * @dataProvider currencyConversionDataProvider
     */
    public function testCurrencyConversion1(string $currency, float $amountBeforeConversion, float $expectedResultAfterConversion)
    {
        $dotenv = Dotenv\Dotenv::createImmutable(realpath("./"));
        $dotenv->load();
        $apiKey = $_ENV["CURRENCY_CONVERTER_API_KEY"];

        $currencyConverter = new CurrconvCurrencyConverter($apiKey);
        $result = $currencyConverter->convertCurrency($currency, "SAR", $amountBeforeConversion);
        $this->assertEquals($expectedResultAfterConversion, $result);
    }

    public function currencyConversionDataProvider(): array
    {
        return [
            "USD Conversion" => ["USD", 100, 375.3],
            "EUR Conversion" => ["EUR", 100, 422.89]
        ];
    }
}
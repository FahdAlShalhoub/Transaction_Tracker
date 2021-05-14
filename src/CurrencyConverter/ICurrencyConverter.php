<?php

namespace CurrencyConverter;

interface ICurrencyConverter
{
    public function convertCurrency($fromCurrency, $toCurrency, $amount): float;
}
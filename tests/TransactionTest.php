<?php

use PHPUnit\Framework\TestCase;
use TransactionManager\Transaction;

final class TransacionTest extends TestCase 
{

    public function testConstructor()
    {
        $date = new \DateTime("NOW");
        $transaction = new Transaction(556.00, "SAR", $date, "Nandos", 4343);

        $this->assertEquals(556.00, $transaction->getAmount());
        $this->assertEquals("SAR", $transaction->getCurrency());
        $this->assertEquals($transaction->getTimestamp(), $date);
        $this->assertEquals("Nandos", $transaction->getVendor());
        $this->assertEquals(4343, $transaction->getCardNumber());
    }

    public function testWrongAmountTypeException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Incorrect Data Type: 556.00 Must Be A double");
        $transaction = new Transaction("556.00", "SAR",new \DateTime("NOW"), "Nandos", 4343);
    }

    public function testWrongCurrencyTypeException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Incorrect Data Type: 4457 Must Be A string");
        $transaction = new Transaction(556.00, 4457, new \DateTime("NOW"), "Nandos", 4343);
    }

    public function testWrongVendorDataTypeException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Incorrect Data Type: 1 Must Be A string");
        $transaction = new Transaction(556.00, "SAR", new \DateTime("NOW"), true, 4343);
    }

    public function testWrongCardNumberDataTypeException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Incorrect Data Type: 4343 Must Be An int");
        $transaction = new Transaction(556.00, "SAR", new \DateTime("NOW"), "Nandos", "4343");
    }

}
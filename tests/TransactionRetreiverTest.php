<?php 
use PHPUnit\Framework\TestCase;
use TransactionManager\TransactionRetreiver;
use TransactionManager\Transaction;
use TransactionManager\TransactionRetreivalStrategy;

final class TransactionRetreiverTest extends TestCase 
{
    public function testGetTransactions()
    {
        $dummyTransactions = [
            new Transaction(50.00, "SAR", new \DateTime("NOW"), "Panda",5570),
            new Transaction(5.50, "USD", new \DateTime("NOW"), "Panda", 3461),
            new Transaction(30.00, "EUR", new \DateTime("NOW"), "Ali Express", 4685)
        ];

        $stubStrategy = $this->createStub(TransactionRetreivalStrategy::class);
        $stubStrategy->method("retreiveTransactions")
                     ->willReturn($dummyTransactions);

        $transactionRetreiver = new TransactionRetreiver($stubStrategy);
        $transactions = $transactionRetreiver->getTransactions();

        $this->assertIsArray($transactions);
        $this->assertNotEmpty($transactions);
        $this->assertEquals(3, count($transactions));

        $transaction_1 = $transactions[0];
        $transaction_2 = $transactions[1]; 
        $transaction_3 = $transactions[2];
        
        $this->assertEquals(50.00, $transaction_1->amount);
        $this->assertEquals("SAR", $transaction_1->currency);
        $this->assertInstanceOf(DateTime::class, $transaction_1->timestamp);
        $this->assertEquals("Panda", $transaction_1->vendor);
        $this->assertEquals(5570, $transaction_1->cardNumber);

        $this->assertEquals(5.50, $transaction_2->amount);
        $this->assertEquals("USD", $transaction_2->currency);
        $this->assertInstanceOf(DateTime::class, $transaction_2->timestamp);
        $this->assertEquals("Panda", $transaction_2->vendor);
        $this->assertEquals(3461, $transaction_2->cardNumber);
        
        $this->assertEquals(30.00, $transaction_3->amount);
        $this->assertEquals("EUR", $transaction_3->currency);
        $this->assertInstanceOf(DateTime::class, $transaction_3->timestamp);
        $this->assertEquals("Ali Express", $transaction_3->vendor);
        $this->assertEquals(4685, $transaction_3->cardNumber);

    }

}
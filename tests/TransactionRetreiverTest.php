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
            new Transaction(50.00, new \DateTime("NOW"), "Panda",5570),
            new Transaction(5.50, new \DateTime("NOW"), "Panda", 3461),
            new Transaction(30.00, new \DateTime("NOW"), "Ali Express", 4685)
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
        
        $this->assertEquals($transaction_1->amount, 50.00);
        $this->assertInstanceOf(DateTime::class, $transaction_1->timestamp);
        $this->assertEquals($transaction_1->vendor, "Panda");
        $this->assertEquals($transaction_1->cardNumber, 5570);

        $this->assertEquals($transaction_2->amount, 5.50);
        $this->assertInstanceOf(DateTime::class, $transaction_2->timestamp);
        $this->assertEquals($transaction_2->vendor, "Panda");
        $this->assertEquals($transaction_2->cardNumber, 3461);
        
        $this->assertEquals($transaction_3->amount, 30.00);
        $this->assertInstanceOf(DateTime::class, $transaction_3->timestamp);
        $this->assertEquals($transaction_3->vendor, "Ali Express");
        $this->assertEquals($transaction_3->cardNumber, 4685);

    }

}
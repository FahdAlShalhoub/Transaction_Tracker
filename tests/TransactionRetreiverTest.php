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

        $transaction_1 = json_decode($transactions[0], true);
        $transaction_2 = json_decode($transactions[1], true); 
        $transaction_3 = json_decode($transactions[2], true);
        
        $this->assertEquals($transaction_1["amount"], 50.00);
        $this->assertIsArray($transaction_1["timestamp"]);
        $this->assertEquals($transaction_1["vendor"], "Panda");
        $this->assertEquals($transaction_1["card_number"], 5570);

        $this->assertEquals($transaction_2["amount"], 5.50);
        $this->assertIsArray($transaction_2["timestamp"]);
        $this->assertEquals($transaction_2["vendor"], "Panda");
        $this->assertEquals($transaction_2["card_number"], 3461);
        
        $this->assertEquals($transaction_3["amount"], 30.00);
        $this->assertIsArray($transaction_3["timestamp"]);
        $this->assertEquals($transaction_3["vendor"], "Ali Express");
        $this->assertEquals($transaction_3["card_number"], 4685);

    }
}
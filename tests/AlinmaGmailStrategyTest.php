<?php 
use PHPUnit\Framework\TestCase;
use TransactionManager\AlinmaGmailStrategy;
use TransactionManager\Transaction;

final class AlinmaGmailStrategyTest extends TestCase
{
    
    public function testRetreiveTransactions() 
    {
       $google = AlinmaGmailStrategy::getInstance();
       $transactions = $google->retreiveTransactions();

       $this->assertIsArray($transactions);
       
       foreach($transactions as $transaction){ 
           $this->assertInstanceOf(Transaction::class,$transaction);

           $this->assertIsFloat($transaction->getAmount());
           $this->assertIsString($transaction->getCurrency());
           $this->assertContains($transaction->getCurrency(), ["SAR", "USD"]);
           $this->assertInstanceOf(DateTime::class,$transaction->getTimestamp());
           $this->assertIsString($transaction->getVendor());
           $this->assertNotEquals("", $transaction->getVendor());
           $this->assertIsInt($transaction->getCardNumber());
       }
    }
}
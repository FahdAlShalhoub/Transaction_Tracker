<?php 
use PHPUnit\Framework\TestCase;
use TransactionManager\GmailStrategy;
use TransactionManager\Transaction;

final class GmailStrategyTest extends TestCase
{
    
    public function testWritesNewMessagesFile() 
    {
       $google = GmailStrategy::getInstance();
       $transactions = $google->retreiveTransactions();

       $this->assertIsArray($transactions);
       
       foreach($transactions as $transaction){ 
           $this->assertInstanceOf(Transaction::class,$transaction);

           $this->assertIsFloat($transaction->getAmount());
           $this->assertInstanceOf(DateTime::class,$transaction->getTimestamp());
           $this->assertIsString($transaction->getVendor());
           $this->assertIsInt($transaction->getCardNumber());
       }
    }
}
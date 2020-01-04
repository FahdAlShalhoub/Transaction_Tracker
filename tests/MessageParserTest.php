<?php 

use PHPUnit\Framework\TestCase;
use MessageParser as Parser;

final class MessageParserTest extends TestCase 
{
   
    public function testReadMessagesData()
    {
        $messages = Parser::readMessagesData("./tests/messagesExample.json");
        $messagesExample = json_decode(file_get_contents("./tests/messagesExample.json"));

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
        $this->assertEquals(count($messagesExample),count($messages));
        
        foreach($messages as $message){
            $this->assertIsString($message);
        }
        
    }
}
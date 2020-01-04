<?php 
use PHPUnit\Framework\TestCase;
use IO\API\GoogleApi as GoogleApi;

final class GoogleApiTest extends TestCase
{
    public static function tearDownAfterClass(): void 
    {
        unlink("./messages.json");
    }

    public function testWritesNewMessagesFile() 
    {
       $google = new GoogleApi();
       $google->getMessages();
       $this->assertFileExists("./messages.json");
       $messages = Json_decode(file_get_contents("./messages.json"));
       $this->assertIsArray($messages);
       foreach($messages as $message){ 
           $this->assertObjectHasAttribute('attachmentId',$message);
           $this->assertObjectHasAttribute('data',$message);
           $this->assertObjectHasAttribute('size',$message);

           $this->assertNull($message->attachmentId);
           $this->assertRegExp("/^[-A-Za-z0-9+=]{1,50}|=[^=]|={3,}$/",$message->data);
       }
    }
}
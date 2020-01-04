<?php

class MessageParser
{
    
    public function readMessagesData($messagesPath)
    {
        $contents = json_decode(file_get_contents($messagesPath));
        $messagesData =  array();

        foreach($contents as $message){
            array_push($messagesData,$message->data);
        }

        return $messagesData; 
    }
    
}
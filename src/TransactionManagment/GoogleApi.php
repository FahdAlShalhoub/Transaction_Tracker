<?php

namespace IO\API;

class GoogleApi
{
    private $client;
    private $messagesPath;
    private $gmailService;

    public function __construct($credentialsPath = "./credentails.json",$messagesPath = "./messages.json",$tokenPath = "./token.json")
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Transaction_Tracker');
        $this->client->setScopes(\Google_Service_Gmail::GMAIL_READONLY);
        $this->client->setAuthConfig('credentials.json');
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $token = file_get_contents($tokenPath);
        
        $this->client->setAccessToken($token);

        if($this->client->isAccessTokenExpired()){
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        }

        $this->gmailService = new \Google_Service_Gmail($this->client);
        
    }

    public function getMessages()
    {
        $messages  = array(); 
        $response = $this->gmailService->users_messages->listUsersMessages("me",[ "q" => "from:alinma@alinma.com newer_than:1d subject:pos_purchase"])->getMessages();

        foreach($response as $message){
            $messageSnippet = $this->gmailService->users_messages->get("me",$message->getId())->getPayload()->getBody(); 
            
            array_push($messages,$messageSnippet);
        }

        file_put_contents("./messages.json",json_encode($messages));
    }
}
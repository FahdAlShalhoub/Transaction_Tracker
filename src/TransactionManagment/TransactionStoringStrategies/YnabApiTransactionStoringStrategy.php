<?php

namespace TransactionManager;

class YnabApiTransactionStoringStrategy extends ApiTransactionStoringStrategy
{
    private $token;

    public function __construct(string $token)
    {
        parent::__construct();
        $this->token = $token;
    }

    protected function getUrl(): string
    {
       return "https://api.youneedabudget.com/v1/budgets/581c7f77-8db8-46b2-8c70-0a9fd2f34b25/transactions";
    }

    protected function getHttpMethod(): string
    {
        return "POST";
    }

    protected function getBody(Transaction $transaction): array
    {
        return [
            "transaction" => [
                "account_id" => "7ba9747b-4202-4343-b378-0ee5ddadeef5",
                "date" => $transaction->timestamp->format("Y-m-d\TH:i:s.u"),
                "amount" => "-" . ($transaction->amount * 1000),
                "payee_name" => $transaction->vendor,
                "approved" => false,
                "memo" => "This is an auto-generated transaction",
                "category_id" => "bd098abf-2378-48ee-9b9f-fc39d6a1e357"
            ]
        ];
    }

    protected function getHeaders(): array
    {
        return [
            "Authorization" => "Bearer " . $this->token,
            "Content-Type" => "application/json"
        ];
    }
}
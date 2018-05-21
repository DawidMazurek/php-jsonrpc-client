<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

class JsonRpcRequestResponse implements JsonRpcResponse
{
    private $requestId = '';
    private $result;

    public function __construct(array $response)
    {
        $this->requestId = (string)$response['id'];
        $this->result = $response['result'];
    }

    public function getId(): string
    {
        return $this->requestId;
    }

    public function getResult(): array
    {
        return [
            'id' => $this->requestId,
            'result' => $this->result
        ];
    }
}

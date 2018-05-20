<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

class JsonRpcRequestError implements JsonRpcResponse
{
    private $requestId = '';
    private $errorCode = 0;
    private $errorMessage = '';

    public function __construct(array $response)
    {
        $this->requestId = (string)$response['id'];
        $this->errorCode = $response['error']['code'];
        $this->errorMessage = $response['error']['message'];
    }

    public function getId(): string
    {
        return $this->requestId;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getResult(): array
    {
        return [
            'id' => $this->requestId,
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->errorMessage
            ]
        ];
    }
}

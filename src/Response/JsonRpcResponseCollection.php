<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

use DawidMazurek\JsonRpcClient\Request\JsonRpcRequest;
use DawidMazurek\JsonRpcClient\Request\JsonRpcRequestCollection;

class JsonRpcResponseCollection
{
    /**
     * @var JsonRpcRequestCollection
     */
    private $requests;

    /**
     * @var array
     */
    private $responses;

    public function __construct(JsonRpcRequestCollection $requests)
    {
        $this->requests = $requests;
        $this->responses = [];
    }

    public function addResponse(JsonRpcResponse $response)
    {
        $this->responses[$response->getId()] = $response;
    }

    public function getResponseFor(JsonRpcRequest $request): JsonRpcRequestResponse
    {
        $offset = $this->requests->getRequestId($request);
        return $this->responses[$offset];
    }

    public function hasRequestFailed(JsonRpcRequest $request): bool
    {
        $offset = $this->requests->getRequestId($request);
        return $this->responses[$offset] instanceof JsonRpcRequestError;
    }
}

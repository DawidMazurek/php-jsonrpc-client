<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

use DawidMazurek\JsonRpcClient\Request\JsonRpcRequest;
use DawidMazurek\JsonRpcClient\Request\JsonRpcRequestCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JsonRpcResponseCollectionTest extends TestCase
{
    /**
     * @var MockObject|JsonRpcRequestCollection
     */
    private $requests;

    /**
     * @var MockObject|JsonRpcRequest
     */
    private $request;

    public function setUp()
    {
        $this->requests = $this->createMock(JsonRpcRequestCollection::class);
        $this->request = $this->createMock(JsonRpcRequest::class);
    }

    /**
     * @test
     */
    public function returnsResponseForGivenRequest()
    {
        $response = new JsonRpcRequestResponse(['id' => 1, 'result' => 1]);
        $this->requests->method('getRequestId')->willReturn(1);

        $responses = new JsonRpcResponseCollection($this->requests);
        $responses->addResponse($response);

        $this->assertSame($response, $responses->getResponseFor($this->request));
    }

    /**
     * @test
     */
    public function detectsFailedResponseWhenErrorObjectGiven()
    {
        $response = $this->createMock(JsonRpcRequestError::class);
        $response->method('getId')->willReturn(1);
        $this->requests->method('getRequestId')->willReturn(1);

        $responses = new JsonRpcResponseCollection($this->requests);
        $responses->addResponse($response);

        $this->assertTrue($responses->hasRequestFailed($this->request));
    }

    /**
     * @test
     */
    public function notDetectsFailedResponseWhenResponseObjectGiven()
    {
        $response = $this->createMock(JsonRpcRequestResponse::class);
        $response->method('getId')->willReturn(1);
        $this->requests->method('getRequestId')->willReturn(1);

        $responses = new JsonRpcResponseCollection($this->requests);
        $responses->addResponse($response);

        $this->assertFalse($responses->hasRequestFailed($this->request));
    }
}

<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Client;

use DawidMazurek\JsonRpcClient\Boundary\Http\RequestFactory;
use DawidMazurek\JsonRpcClient\Exception\NoResponseForGivenRequest;
use DawidMazurek\JsonRpcClient\Request\JsonRpcNotification;
use DawidMazurek\JsonRpcClient\Request\JsonRpcRequest;
use DawidMazurek\JsonRpcClient\Request\JsonRpcRequestCollection;
use Http\Client\HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class JsonRpcClientTest extends TestCase
{
    /**
     * @var MockObject|HttpClient
     */
    private $httpClient;

    /**
     * @var MockObject|RequestFactory
     */
    private $requestFactory;

    /**
     * @var MockObject|JsonRpcRequestCollection
     */
    private $requests;

    public function setUp()
    {
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->requestFactory = $this->createMock(RequestFactory::class);
        $this->requests = $this->createMock(JsonRpcRequestCollection::class);
    }

    /**
     * @test
     */
    public function sendsRequest()
    {
        $request1 = $this->createMock(JsonRpcRequest::class);
        $request2 = $this->createMock(JsonRpcRequest::class);
        $requests = new JsonRpcRequestCollection();
        $requests->addRequest($request1);
        $requests->addRequest($request2);

        $response = $this->createMock(ResponseInterface::class);
        $responseBody = $this->createMock(StreamInterface::class);

        $jsonRpcResponse = '[{"jsonrpc":2.0,"id":1,"result":"abc"},{"jsonrpc":2.0,"id":2,"result":"abc"}]';
        $response->method('getBody')->willReturn($responseBody);
        $responseBody->method('getContents')->willReturn($jsonRpcResponse);

        $this->httpClient->method('sendRequest')
            ->willReturn($response);

        $client = new JsonRpcClient(
            $this->httpClient,
            $this->requestFactory
        );

        $response = $client->execute($requests);
        $response1 = $response->getResponseFor($request1);
        $response2 = $response->getResponseFor($request2);
        $this->assertEquals('abc', $response1->getResult());
        $this->assertEquals(1, $response1->getId());
        $this->assertEquals('abc', $response2->getResult());
        $this->assertEquals(2, $response2->getId());
    }


    /**
     * @test
     */
    public function sendsNotification()
    {
        $notification = $this->createMock(JsonRpcNotification::class);
        $requests = new JsonRpcRequestCollection();
        $requests->addRequest($notification);

        $response = $this->createMock(ResponseInterface::class);
        $responseBody = $this->createMock(StreamInterface::class);

        $jsonRpcResponse = '';
        $response->method('getBody')->willReturn($responseBody);
        $responseBody->method('getContents')->willReturn($jsonRpcResponse);

        $this->httpClient->method('sendRequest')
            ->willReturn($response);

        $client = new JsonRpcClient(
            $this->httpClient,
            $this->requestFactory
        );

        $responses = $client->execute($requests);

        $this->expectException(NoResponseForGivenRequest::class);
        $responses->getResponseFor($notification);
    }
}

<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Client;

use DawidMazurek\JsonRpcClient\Boundary\Http\RequestFactory;
use DawidMazurek\JsonRpcClient\Exception\RequestWithoutId;
use DawidMazurek\JsonRpcClient\Request\JsonRpcRequestCollection;
use DawidMazurek\JsonRpcClient\Response\JsonRpcRequestError;
use DawidMazurek\JsonRpcClient\Response\JsonRpcRequestResponse;
use DawidMazurek\JsonRpcClient\Response\JsonRpcResponse;
use DawidMazurek\JsonRpcClient\Response\JsonRpcResponseCollection;
use Http\Client\HttpClient;

class JsonRpcClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    public function __construct(
        HttpClient $httpClient,
        RequestFactory $requestFactory
    ){
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function execute(JsonRpcRequestCollection $requests): JsonRpcResponseCollection
    {
        $requestPayload = [];

        foreach ($requests->getAllRequests() as $request) {
            $payload = [
                'jsonrpc' => '2.0',
                'method' =>  $request->getMethodName(),
                'params' => $request->getParams()
            ];

            if ($requests->requestHasId($request)) {
                try {
                    $payload['id'] = $requests->getRequestId($request);
                } catch(RequestWithoutId $exception) {
                }
            }

            $requestPayload []= $payload;
        }

        if (count($requestPayload) === 1) {
            $requestPayload = reset($requestPayload);
        }

        $response = $this->httpClient->sendRequest(
            $this->requestFactory->createPostRequest($requestPayload)
        );

        $parsedResponse = json_decode(((string)$response->getBody()->getContents()), true);
        
        $responses = new JsonRpcResponseCollection($requests);

        if (!empty($parsedResponse)) {
            foreach ($parsedResponse as $singleResponse) {
                $responses->addResponse(
                    $this->createResultObject($singleResponse)
                );
            }
        }

        return $responses;
    }

    private function createResultObject(array $responseData): JsonRpcResponse
    {
        if (array_key_exists('result', $responseData)) {
            return new JsonRpcRequestResponse($responseData);
        }

        return new JsonRpcRequestError($responseData);
    }
}

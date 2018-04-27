<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

use PHPUnit\Framework\TestCase;

class JsonRpcRequestErrorTest extends TestCase
{
    /**
     * @test
     */
    public function deliversConstructParamsToGetters()
    {
        $requestId = 1;
        $errorCode = 1;
        $errorMessage = 'error message';

        $response = new JsonRpcRequestError(
            [
                'id' => $requestId,
                'error' => [
                    'code' => $errorCode,
                    'message' => $errorMessage
                ]
            ]
        );

        $this->assertEquals($requestId, $response->getId());
        $this->assertEquals($errorCode, $response->getErrorCode());
        $this->assertEquals($errorMessage, $response->getErrorMessage());
    }
}

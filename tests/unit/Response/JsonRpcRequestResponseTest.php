<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

use PHPUnit\Framework\TestCase;

class JsonRpcRequestResponseTest extends TestCase
{
    /**
     * @test
     */
    public function deliversConstructParamsToGetters()
    {
        $requestId = 1;
        $result = ['res' => 'result payload'];

        $response = new JsonRpcRequestResponse(
            [
                'id' => $requestId,
                'result' => $result,
            ]
        );

        $this->assertEquals($requestId, $response->getId());
        $this->assertEquals($result, $response->getResult());
    }
}

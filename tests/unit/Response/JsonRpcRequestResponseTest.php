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

        $expected = [
            'id' => $requestId,
            'result' => $result,
        ];

        $response = new JsonRpcRequestResponse($expected);

        $this->assertEquals($requestId, $response->getId());
        $this->assertEquals($expected, $response->getResult());
    }
}

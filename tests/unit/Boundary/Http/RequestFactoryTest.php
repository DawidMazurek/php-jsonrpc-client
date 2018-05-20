<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Boundary\Http;

use DawidMazurek\JsonRpcClient\Client\JsonRpcClientConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;

class RequestFactoryTest extends TestCase
{
    /**
     * @var MockObject|JsonRpcClientConfiguration
     */
    private $config;

    public function setUp()
    {
        $this->config = $this->createMock(JsonRpcClientConfiguration::class);
    }
    /**
     * @test
     */
    public function createsValidRequestObject()
    {
        $factory = new RequestFactory($this->config);
        $request = $factory->createPostRequest(['sample' => 'payload']);

        $this->assertInstanceOf(MessageInterface::class, $request);
    }
}

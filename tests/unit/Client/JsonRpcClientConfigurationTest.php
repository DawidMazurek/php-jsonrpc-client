<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Client;

use PHPUnit\Framework\TestCase;

class JsonRpcClientConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function passesCorrectUri()
    {
        $expected = 'http://ex.am.ple.com';
        $config = ['uri' => $expected];
        $configObject = new JsonRpcClientConfiguration($config);
        $this->assertEquals($expected, $configObject->getUri());
    }
}

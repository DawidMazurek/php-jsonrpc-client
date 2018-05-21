<?php

declare(strict_types=1);

namespace DawidMazurek\JsonRpcClient\Response;

interface JsonRpcResponse
{
    public function getId(): string;
    public function getResult(): array;
}

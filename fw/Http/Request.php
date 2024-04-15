<?php

namespace Fw\PhpFw\Http;

class Request
{
    public function __construct(
        private readonly array $getParams,
        private readonly array $postData,
        private readonly array $cookie,
        private readonly array $server,
        private readonly array $files,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_COOKIE,
            $_SERVER,
            $_FILES
        );
    }

    public function getPath(): string
    {
        return strtok($this->server["REQUEST_URI"],'?');
    }

    public function getMethod(): string
    {
        return $this->server["REQUEST_METHOD"];
    }
}
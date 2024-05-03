<?php

namespace Fw\PhpFw\Http;

use Fw\PhpFw\Session\SessionInterface;

class Request
{

    private SessionInterface $session;
    public function __construct(
        private readonly array $getParams,
        public readonly array $postData,
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

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
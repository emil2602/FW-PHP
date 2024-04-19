<?php

namespace Fw\PhpFw\Http;

class Response
{
    public function __construct(
        private mixed $content = '',
        private int $statusCode = 200,
        private array $headers = []
    )
    {
    }

    public function send()
    {
        echo $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

}
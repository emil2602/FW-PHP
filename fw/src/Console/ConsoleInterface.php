<?php

namespace Fw\PhpFw\Console;

interface ConsoleInterface
{
    public function execute(array $params = []): int;
}
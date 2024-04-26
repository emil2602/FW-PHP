<?php

namespace Fw\PhpFw\Console\Commands;

class MigrateCommand implements \Fw\PhpFw\Console\ConsoleInterface
{

    private string $name = 'migrate';
    public function execute(array $params = []): int
    {
        var_dump($params);

        return 0;
    }
}
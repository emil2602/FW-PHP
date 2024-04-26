<?php

namespace Fw\PhpFw\Console;

use Psr\Container\ContainerInterface;

class Kernel
{

    public function __construct(
        private ContainerInterface $container,
        private Application $application
    )
    {
    }

    public function handle()
    {
        $this->registerCommands();

        $status = $this->application->run();

        return $status;
    }

    private function registerCommands()
    {
        $commandFiles = new \DirectoryIterator(__DIR__.'/Commands');
        $namespace = $this->container->get('framework-cmd-namespace');

        /** @var \DirectoryIterator $commandFile */

        foreach ($commandFiles as $commandFile) {
            if(!$commandFile->isFile()) {
                continue;
            }

            $command = $namespace.pathinfo($commandFile, PATHINFO_FILENAME);

            if (is_subclass_of($command, ConsoleInterface::class)) {
                $name = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();

                $this->container->add($name, $command);
            }
        }
    }

}
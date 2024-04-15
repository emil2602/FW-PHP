<?php

namespace Fw\PhpFw\Tests;

use Fw\PhpFw\Container\ContainerException;
use PHPUnit\Framework\TestCase;
use Fw\PhpFw\Container\Container;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {

        $container = new Container();

        $container->add('fw-class', Fw::class);

        $this->assertInstanceOf(Fw::class, $container->get('fw-class'));
    }

    public function test_container_exception_if_add_wrong_service()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('not-exist-class');
    }

    public function test_has_method()
    {
        $container = new Container();

        $container->add('fw-class', Fw::class);

        $this->assertTrue($container->has('fw-class'));
        $this->assertFalse($container->has('not-exist-class'));
    }

}
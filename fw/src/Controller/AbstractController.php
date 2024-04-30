<?php

namespace Fw\PhpFw\Controller;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ContainerInterface $container;
    protected Request $request;


    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function render(string $view, array $params = [], Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($view, $params);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}
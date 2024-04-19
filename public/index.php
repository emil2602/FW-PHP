<?php

require_once  dirname(__DIR__).'/vendor/autoload.php';

use Fw\PhpFw\Http\Request;

define('BASE_PATH', dirname(__DIR__));

/** @var \League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

$kernel = $container->get(\Fw\PhpFw\Http\Kernel::class);

$response = $kernel->handle(Request::createFromGlobals());

$response->send();


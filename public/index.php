<?php

require_once  dirname(__DIR__).'/vendor/autoload.php';

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Kernel;
use Fw\PhpFw\Routing\Router;

define('BASE_PATH', dirname(__DIR__));

$router = new Router();

$kernel = new Kernel($router);
$response = $kernel->handle(Request::createFromGlobals());

$response->send();


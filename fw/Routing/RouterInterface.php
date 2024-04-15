<?php

namespace Fw\PhpFw\Routing;

use Fw\PhpFw\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
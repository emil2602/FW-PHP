<?php

namespace App\Controllers;

use Fw\PhpFw\Http\Response;

class HomeController
{
    public function index(): Response
    {
        $content = 'shit';

        return new Response($content);
    }
}
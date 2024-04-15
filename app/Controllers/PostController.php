<?php

namespace App\Controllers;

use Fw\PhpFw\Http\Response;

class PostController
{

    public function show(int $id)
    {
        $content = "Shit with" . $id;

        return new Response($content);
    }
}
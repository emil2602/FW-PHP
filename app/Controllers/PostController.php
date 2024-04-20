<?php

namespace App\Controllers;

use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Response;

class PostController extends AbstractController
{

    public function show(int $id)
    {
        return $this->render('post.html.twig', ["id" => $id]);
    }

    public function create()
    {
        return $this->render('create_post.html.twig');
    }
}
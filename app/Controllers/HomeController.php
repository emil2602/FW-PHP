<?php

namespace App\Controllers;

use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Response;

class HomeController extends AbstractController
{

    public function __construct(
    ) {
    }

    public function index(): Response
    {

        return $this->render('home.html.twig', ['a' => 1233]);
    }
}
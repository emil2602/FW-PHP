<?php

namespace App\Controllers;

use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Response;

class LoginController extends AbstractController
{

    public function form(): Response
    {
        return $this->render('login.twig.html');
    }

    public function login()
    {

    }
}
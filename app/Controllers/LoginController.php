<?php

namespace App\Controllers;

use Fw\PhpFw\Authentication\SessionAuthInterface;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\RedirectResponse;
use Fw\PhpFw\Http\Response;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly SessionAuthInterface $sessionAuth
    )
    {
    }

    public function form(): Response
    {
        return $this->render('login.twig.html');
    }

    public function login(): RedirectResponse
    {

        $isAuth = $this->sessionAuth->authenticate(
            $this->request->input('email'),
            $this->request->input('password'),
        );

        if (! $isAuth) {
            $this->request->getSession()->setFlash('error', 'Wrong email or password');

            return new RedirectResponse('/login');
        }

        $this->request->getSession()->setFlash('success', 'Success');

        return new RedirectResponse('/');
    }
}
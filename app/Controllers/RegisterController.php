<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\RedirectResponse;
use Fw\PhpFw\Http\Response;

class RegisterController extends AbstractController
{

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function form(): Response
    {
        return $this->render('register.twig.html');
    }

    public function register()
    {
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('name'),
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation'),
        );

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }
            return new RedirectResponse('/register');
        }

        $user = $form->save();

        $this->request->getSession()->setFlash('success', 'User successfully created');

        return new RedirectResponse('/register');
    }
}
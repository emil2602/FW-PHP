<?php

namespace App\Forms\User;

use App\Entities\User;
use App\Services\UserService;

class RegisterForm
{
    private ?string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function setFields($email, $password, $passwordConfirmation, $name = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function save()
    {
        $user = User::create(
            $this->email,
        password_hash($this->password, PASSWORD_DEFAULT),
            new \DateTimeImmutable(),
            $this->name
        );

        $user = $this->userService->save($user);

        return $user;
    }
    public function getValidationErrors(): array
    {
        $errors = [];

        if (! empty($this->name) && strlen($this->name) > 50) {
            $errors[] = 'The maximum length for the name is 50 characters';
        }

//        if (empty($this->email) || ! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
//            $errors[] = 'Invalid email format';
//        }

        if (empty($this->password) || strlen($this->password) < 8) {
            $errors[] = 'The minimum password length is 8 characters';
        }

//        if ($this->password !== $this->passwordConfirmation) {
//            $errors[] = 'The passwords do not match';
//        }

        return $errors;
    }

    public function hasValidationErrors(): bool
    {
        return ! empty($this->getValidationErrors());
    }
}
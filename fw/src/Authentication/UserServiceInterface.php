<?php

namespace Fw\PhpFw\Authentication;

interface UserServiceInterface
{
    public function findByEmail(string $email): ?AuthUserInterface;
}

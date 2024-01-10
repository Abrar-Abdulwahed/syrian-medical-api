<?php

namespace App\Contracts;

interface Authenticator
{
    public function authenticate($email, $password, $isAdmin);
}
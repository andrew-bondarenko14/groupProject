<?php

namespace App\Repositories;

interface PasswordResetRepository
{
    public function getModel();

    public function getPasswordResetByToken($token);

    public function getPasswordResetByEmail($email);
}

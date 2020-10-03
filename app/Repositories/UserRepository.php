<?php

namespace App\Repositories;

interface UserRepository
{
    public function getModel();

    public function getUserById($id);

    public function getUserByEmail($email);
}

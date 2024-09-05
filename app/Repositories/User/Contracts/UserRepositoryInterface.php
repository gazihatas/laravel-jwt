<?php

namespace App\Repositories\User\Contracts;

use App\Data\UserData;
use App\Models\User;

interface UserRepositoryInterface
{
    public function create(UserData $userData): User;

    public function findByEmail(string $email): ?User;
}

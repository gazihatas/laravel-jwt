<?php

namespace App\Services\Auth\Contracts;

use App\Data\UserData;

interface JWTAuthInterface
{
    public function login(array $credentials): array;
    public function refresh(): array;
    public function register(array $data):UserData;
    public function logout(): void;
}

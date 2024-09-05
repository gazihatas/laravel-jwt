<?php

namespace App\Repositories\User\Implementations;

use App\Data\UserData;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostgreSQLUserRepository implements UserRepositoryInterface
{

    public function create(UserData $userData): User
    {

        $user = DB::table('users')->insertGetId([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => bcrypt($userData->password)
        ]);

        return new User([
            'id' => $user,
            'name' => $userData->name,
            'email' => $userData->email
        ]);


    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}

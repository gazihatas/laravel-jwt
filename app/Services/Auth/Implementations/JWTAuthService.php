<?php

namespace App\Services\Auth\Implementations;

use App\Data\UserData;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Services\Auth\Contracts\JWTAuthInterface;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthService implements JWTAuthInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials): array
    {
        if (!$token = auth()->attempt($credentials)) {
            return [
                'message' => 'E-posta adresi veya şifre hatalı.',
                'error' => 'Unauthorized',
                'status' => 401
            ];
        }

        $user = auth()->user();
        $refreshToken = auth()->setTTL(7200)->refresh();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function refresh(): array
    {
        try {
            $newToken = auth()->refresh();
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return [
                'error' => 'Token could not be refreshed',
                'status' => 401,
            ];
        }
    }

    public function logout(): void
    {
        auth()->invalidate(true);
        auth()->logout();
    }

    protected function respondWithToken($token) : array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function register(array $data): UserData
    {
        $userData = new UserData(
            id: null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );

        $user = $this->userRepository->create($userData);

        return new UserData(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: ''
        );
    }


    public function findOrCreateSocialUser(UserData $userData): UserData
    {
        $existingUser = $this->userRepository->findByEmail($userData->email);

        if ($existingUser) {
            return new UserData(
                id: $existingUser->id,
                name: $existingUser->name,
                email: $existingUser->email,
                password: ''
            );
        }

        return $this->userRepository->create($userData);
    }
}

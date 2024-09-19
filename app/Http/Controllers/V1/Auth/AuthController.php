<?php

namespace App\Http\Controllers\V1\Auth;

use App\Data\UserData;
use App\Enums\LogLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\Auth\Contracts\JWTAuthInterface;
use App\Services\Log\Contracts\LogServiceInterface;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class AuthController extends Controller
{
    use ApiResponseTrait;
    protected JWTAuthInterface $authService;
    protected LogServiceInterface $logService;

    public function __construct(JWTAuthInterface $authService,LogServiceInterface $logService)
    {
        $this->authService = $authService;
        $this->logService = $logService;
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="User registration failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $userData = $this->authService->register($request->validated());

        $this->logService->log(LogLevel::INFO, 'New user registered', [
            'user_id' => $userData->id,
            'email' => $userData->email,
        ]);

        return $this->successResponse($userData->toArray(), 'User successfully registered', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->authService->login($credentials);

        if (isset($result['error'])) {
//            $this->logService->log(LogLevel::WARNING, 'Failed login attempt', [
//                'email' => $credentials['email'],
//            ]);

            Log::channel('mongodb')->warning('Failed login attempt', [
                'email' => $credentials['email'],
            ]);
            return $this->errorResponse($result['message'], $result['status']);
        }

//        $this->logService->log(LogLevel::INFO, 'User logged in', [
//            'user_id' => $result['user']['id'],
//            'email' => $result['user']['email'],
//        ]);

        Log::channel('mongodb')->info('User logged in', [
            'user_id' => $result['user']['id'],
            'email' => $result['user']['email'],
        ]);

        return $this->successResponse($result, 'User logged in successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout the user",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        $user = auth()->user();
        $this->authService->logout();

        if ($user) {
            $this->logService->log(LogLevel::INFO, 'User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        return $this->successResponse(null, 'Successfully logged out');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/refresh",
     *     summary="Refresh JWT token",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        $user = auth()->user();
        $result = $this->authService->refresh();

        if (isset($result['error'])) {
            $this->logService->log(LogLevel::WARNING, 'Token refresh failed', [
                'user_id' => $user ? $user->id : 'N/A',
                'error' => $result['error'],
            ]);

            return $this->errorResponse($result['error'], [], $result['status']);
        }

        $this->logService->log(LogLevel::INFO, 'Token refreshed', [
            'user_id' => $user ? $user->id : 'N/A',
        ]);

        return $this->successResponse($result, 'Token refreshed successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/verify",
     *     summary="Verify authenticated user",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="User is authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unable to authenticate user.")
     *         )
     *     )
     * )
     */
    public function verify()
    {
        $user = auth()->user();

        if ($user) {
            $this->logService->log(LogLevel::INFO, 'User authenticated', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            $userData = new UserData(id : $user->id,name: $user->name,email: $user->email);

            return $this->successResponse(['user' => $userData->toArray()], 'User is authenticated.');
        }

        return $this->errorResponse('Unable to authenticate user.', [], 401);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        return $this->handleSocialLogin($user);
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        return $this->handleSocialLogin($user);
    }


    protected function handleSocialLogin($socialUser)
    {
        $userData = new UserData(id: null,name: $socialUser->getName(),email: $socialUser->getEmail(),password: '');

        $user = $this->authService->findOrCreateSocialUser($userData);

        try {
            $token = $this->authService->login(['email' => $user->email]);
        } catch (Exception $e) {
            return $this->errorResponse('Token creation failed: ' . $e->getMessage(), [], 500);
        }

        return $this->successResponse($token, 'User logged in successfully');
    }



}

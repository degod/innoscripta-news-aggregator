<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\JwtAuthService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Post(
 *     path="/api/v1/auth/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "password", "password_confirmation"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *             @OA\Property(property="password_confirmation", type="string", example="secret123")
 *         )
 *     ),
 *     @OA\Response(response=201, description="User registered successfully"),
 *     @OA\Response(response=422, description="Validation failed")
 * )
 */
class RegisterController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JwtAuthService $jwtAuth,
        private ResponseService $responseService
    ) {}

    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $token = $this->jwtAuth->createToken($user);

        return $this->responseService->success(201, 'User registered successfully', [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
}

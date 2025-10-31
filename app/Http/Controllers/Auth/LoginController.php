<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\JwtAuthService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Post(
 *     path="/api/v1/auth/login",
 *     summary="Authenticate a user and return JWT token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Login successful"),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 */
class LoginController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JwtAuthService $jwtAuth,
        private ResponseService $responseService
    ) {}

    public function __invoke(LoginRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->responseService->error(401, 'Invalid credentials');
        }

        $token = $this->jwtAuth->createToken($user);

        return $this->responseService->success(
            200,
            'Login successful',
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user,
            ]
        );
    }
}

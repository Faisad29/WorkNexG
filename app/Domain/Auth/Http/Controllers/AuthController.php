<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Http\Requests\LoginRequest;
use App\Domain\Auth\Http\Requests\RegisterTenantRequest;
use App\Domain\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(private readonly AuthService $service)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->service->login(
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json([
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }

    public function register(RegisterTenantRequest $request): JsonResponse
    {
        $result = $this->service->registerTenant($request->validated());

        return response()->json([
            'data' => [
                'organization' => $result['organization'],
                'company' => $result['organization'],
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user !== null) {
            $this->service->logout($user);
        }

        return response()->json(['message' => 'logged_out']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    // Inject langsung class AuthService-nya di sini
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $result = $this->authService->register($validated);

        return response()->json([
            'message' => 'Registrasi akun EcoTrack berhasil!',
            'data' => $result
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($validated);

        if (! $result) {
            return response()->json([
                'message' => 'Kredensial yang Anda masukkan salah.',
            ], 401);
        }

        return response()->json([
            'message' => 'Login sukses! Selamat datang kembali.',
            'data' => $result
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Berhasil logout, token akses telah dicabut.'
        ], 200);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ], 200);
    }
}
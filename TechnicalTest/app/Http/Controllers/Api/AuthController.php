<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Ensure role is assigned
        ]);

        return response()->json([
            'token' =>  $user->createToken('Task Management API')->accessToken,
            'user' => $user,
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('Task Management API')->accessToken;

        return response()->json([
            'token' =>  $token,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json(['message' => 'Logged out']);
    }
}

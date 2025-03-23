<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'admin_secret' => 'nullable|string', // Secret key for admin registration
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'is_verified' => false,
            'is_admin' => $request->admin_secret === config('app.admin_secret'),
        ]);

        $this->sendVerificationEmail($user);

        event(new UserRegistered($user));

        return response()->json([
            'message' => 'Registration successful. Please check your email for verification.',
        ], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['error' => 'Account not verified. Please verify your account.'], 403);
        }

        $token = $user->createToken('LaravelPassportToken')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    protected function sendVerificationEmail(User $user)
    {
        $verificationCode = rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::raw("Your verification code is: $verificationCode", function ($message) use ($user) {
            $message->to($user->email)->subject('Email Verification');
        });
    }

    public function verifyCode(Request $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('phone_number', $request->phone_number)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->verification_code != $request->code) {
            return response()->json(['error' => 'Invalid verification code'], 400);
        }

        if (Carbon::now()->gt($user->verification_code_expires_at)) {
            return response()->json(['error' => 'Verification code has expired'], 400);
        }

        $user->is_verified = true;
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Verification successful']);
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->is_verified) {
            return response()->json(['error' => 'Account is already verified'], 400);
        }

        $this->sendVerificationEmail($user);

        return response()->json(['message' => 'Verification email resent successfully.']);
    }
}
<?php
// app/Services/AuthService.php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate user and create token
     */
    public function authenticate(string $email, string $password, string $deviceName): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is active
        if (!$user->active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is disabled. Please contact the administrator.'],
            ]);
        }

        // Create token
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('sanctum.expiration', 525600)),
        ];
    }

    /**
     * Logout user by revoking all tokens
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Create new personal access token for user
     */
    public function createToken(User $user, string $tokenName, array $abilities = ['*']): string
    {
        return $user->createToken($tokenName, $abilities)->plainTextToken;
    }

    /**
     * Register a new user
     */
    public function register(array $data): User
    {
        $user = new User();
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role_id = $data['role_id'];
        $user->client_id = $data['client_id'] ?? null;
        $user->active = true;
        $user->archived = false;
        $user->save();

        return $user;
    }
}
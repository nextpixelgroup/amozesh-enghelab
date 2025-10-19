<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthLoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle an admin login request.
     *
     * @param AuthLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'ایمیل یا رمز عبور اشتباه است',
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('admin-token', ['admin'])->plainTextToken;

        return sendJson(data: [
            'user' => (new AdminResource($user)),
            'token' => $token,
        ]);
    }
}

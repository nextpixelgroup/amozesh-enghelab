<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthLoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    /**
     * Handle an admin login request.
     *
     * @param AuthLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('username', 'password');

            if (!Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'email' => 'ایمیل یا رمز عبور اشتباه است',
                ]);
            }

            $user = User::where('username', $request->username)->firstOrFail();
            // Revoke existing tokens
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('admin-token', ['admin'])->plainTextToken;

            return sendJson(data: [
                'user' => (new AdminResource($user)),
                'token' => $token,
            ]);
        }
        catch (Exception $error){
            return sendJson(status: 'error', message: $error->getMessage());
        }
    }

    public function me()
    {
        return sendJson(data: new AdminResource(Auth::user()));
    }
}

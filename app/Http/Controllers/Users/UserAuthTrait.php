<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Request;
use App\Http\Responses\Response;
use App\Models\System\Config;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;

trait UserAuthTrait
{

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        /** @var User $user */
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return Response::error('Invalid credentials', 401);
        }

        $expiresIn = (int)Config::cache('token_expires_in');

        if (Config::cache('single_session')) {
            $user->tokens()->delete();
        }

        $token = $user->createToken(
            $user->username . '_' . now()->timestamp,
            $user->permissions()->pluck('name')->toArray(),
            now()->addDays($expiresIn)
        )
            ->plainTextToken;

        return Response::success('User logged in successfully', [
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return Response::success('User logged out successfully');
    }
}

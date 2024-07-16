<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller implements HasMiddleware
{
    use UserAuthTrait, UserPermissionTrait, UserRoleTrait;

    public static function middleware()
    {
        $aclRoutes = ['info'];

        $middlewares = [
            new Middleware('auth:sanctum', except: ['register', 'login'])
        ];

        foreach ($aclRoutes as $route) {
            $middlewares[] = new Middleware('acl:user.' . $route, only: [$route]);
        }

        return $middlewares;
    }

    public function index(): JsonResponse
    {
        $users = User::all();
        return Response::success('Users retrieved successfully', ['users' => $users]);
    }

    public function show(Request $request): JsonResponse
    {
        if (!$request->has('id')) {
            $user = $request->user();
        } else {
            $user = User::findOrFail($request->input('id'));
        }

        $user->load('roles.permissions');

        return Response::success('User info retrieved successfully', ['user' => $user]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => get_password_rules(),
            'confirm_password' => 'required|same:password',
        ]);

        if (User::where('username', $request->input('username'))->exists()) {
            return Response::error('Username already exists', 409);
        }

        $user = User::create([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ]);

        return Response::success('User registered successfully', ['user' => $user]);
    }

    public function update(Request $request): JsonResponse
    {
        $passwordChanged = false;

        if (!$request->has('id')) {
            $user = $request->user();
        } else {
            $user = User::findOrFail($request->input('id'));
        }

        if ($request->has('password')) {
            $request->validate([
                'new_password' => get_password_rules(),
                'confirm_password' => 'required|same:new_password',
            ]);

            if (!Hash::check($request->input('password'), $user->password)) {
                return Response::error('Invalid password', 401);
            }

            $user->password = bcrypt($request->input('new_password'));
            $user->save();
            $user->tokens()->delete();
            $passwordChanged = true;
        }

        $inputs = $request->all();
        $updates = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $user->getFillable())) {
                $updates[$key] = $value;
            }
        }

        if (empty($updates) && !$passwordChanged) {
            return Response::error('No valid data found to update', 422);
        }

        $user->update($updates);

        return Response::success('User profile updated successfully', ['user' => $user]);
    }

    public function delete(Request $request): JsonResponse
    {
        if (!$request->has('id')) {
            $user = $request->user();
        } else {
            $user = User::findOrFail($request->input('id'));
        }

        $user->delete();

        return Response::success('User deleted successfully');
    }
}

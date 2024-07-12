<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    use UserAuthTrait, UserPermissionTrait, UserRoleTrait;

    public function info(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('roles.permissions');
        return Response::success('User info retrieved successfully', ['user' => $user]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $inputs = $request->all();
        $updates = [];

        foreach ($inputs as $key => $value) {
            if ($key !== 'password' && in_array($key, $user->getFillable())) {
                $updates[$key] = $value;
            }
        }

        if (empty($updates)) {
            return Response::error('No valid data found to update', 422);
        }

        $user->update($updates);

        return Response::success('User profile updated successfully', ['user' => $user]);
    }
}

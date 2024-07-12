<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;

trait UserRoleTrait
{
    public function roles(Request $request): JsonResponse
    {
        $user = $request->user();
        $roles = $user->roles->pluck('name', 'id')->toArray();
        return Response::success('User roles retrieved successfully', ['roles' => $roles]);
    }

    public function assignRoles(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->roles()->sync($request->roles);
        return Response::success('User roles assigned successfully');
    }
}

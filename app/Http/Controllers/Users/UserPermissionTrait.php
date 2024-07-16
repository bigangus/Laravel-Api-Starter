<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;

trait UserPermissionTrait
{
    public function permissions(Request $request): JsonResponse
    {
        $user = $request->user();
        $permissions = $user->permissions()->pluck('name')->toArray();
        return Response::success('User direct permissions retrieved successfully', ['permissions' => $permissions]);
    }
}

<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use App\Models\Users\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return (new static)->getMiddleware('permission');
    }

    public function index(): JsonResponse
    {
        return Response::success('Permissions fetched successfully', Permission::all());
    }

    public function show(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer']);

        $permission = Permission::findOrFail($request->input('id'));

        return Response::success('Permission retrieved successfully', ['permission' => $permission]);
    }
}

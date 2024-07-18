<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use App\Models\Users\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return (new static)->getMiddleware('role');
    }

    public function index(): JsonResponse
    {
        $roles = Role::all();

        return Response::success('Roles retrieved successfully', $roles);
    }

    public function show(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer']);

        $role = Role::findOrFail($request->input('id'));

        return Response::success('Role retrieved successfully', $role);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string'
        ]);

        $role = Role::findOrFail($request->input('id'));

        $role->update($request->only(['name']));

        return Response::success('Role updated successfully', $role);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $role = Role::create($request->only(['name']));

        return Response::success('Role created successfully', $role);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer']);

        $role = Role::findOrFail($request->input('id'));

        $role->delete();

        return Response::success('Role deleted successfully');
    }
}

<?php

namespace Database\Seeders;

use App\Models\System\Dictionary;
use App\Models\Users\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(Route::getRoutes())->each(function (\Illuminate\Routing\Route $route) {
            $middlewares = $route->gatherMiddleware();
            foreach ($middlewares as $middleware) {
                if (str_starts_with($middleware, 'acl:')) {
                    $permission = substr($middleware, strlen('acl:'));
                    Permission::firstOrCreate(['name' => $permission]);
                }
            }
        });

        $permissions = Permission::all()->toArray();

        foreach ($permissions as &$permission) {
            $permission['translation'] = Arr::join(
                Arr::map(
                    explode('.', $permission['name']),
                    fn($value) => ucfirst($value)
                ),
                ' '
            );
        }

        Dictionary::updateOrCreate(
            [
                'key' => 'permissions'
            ],
            [
                'name' => 'Permission List',
                'value' => collect($permissions)->toJson()
            ]
        );
    }
}

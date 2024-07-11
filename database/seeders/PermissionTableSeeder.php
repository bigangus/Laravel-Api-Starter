<?php

namespace Database\Seeders;

use App\Models\Users\Permission;
use Illuminate\Database\Seeder;
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
    }
}

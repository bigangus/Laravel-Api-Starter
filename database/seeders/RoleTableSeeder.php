<?php

namespace Database\Seeders;

use App\Models\Users\Permission;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin']
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        Role::where('name', 'admin')->first()->permissions()->sync(Permission::all()->pluck('id')->toArray());
        User::where('username', 'admin')->first()->roles()->sync(Role::all()->pluck('id')->toArray());
    }
}

<?php

namespace Tests\Feature\Users;

use App\Models\Users\User;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    public function testUserCanLogin()
    {
        $username = fake()->userName();
        $password = fake()->password();

        User::create([
            'username' => $username,
            'password' => bcrypt($password),
        ]);

        $response = $this->post('api/users/login', [
            'username' => $username,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }
}

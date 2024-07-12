<?php

namespace Tests\Feature\Users;

use Tests\TestCase;

class UserAuthTest extends TestCase
{
    public function testUserCanRegister()
    {
        $username = fake()->userName();
        $password = fake()->password();

        $response = $this->post('api/users/register', [
            'username' => $username,
            'password' => $password,
            'confirm_password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function testUserCanLogin()
    {
        $username = fake()->userName();
        $password = fake()->password();

        $this->post('api/users/register', [
            'username' => $username,
            'password' => $password,
            'confirm_password' => $password,
        ]);

        $response = $this->post('api/users/login', [
            'username' => $username,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function testUserCanUpdatePassword()
    {
        $username = fake()->userName();
        $password = fake()->password();
        $newPassword = fake()->password();

        $this->post('api/users/register', [
            'username' => $username,
            'password' => $password,
            'confirm_password' => $password,
        ]);

        $response = $this->post('api/users/login', [
            'username' => $username,
            'password' => $password,
        ]);

        $token = $response['data']['token'];

        $response = $this->withToken($token)
            ->post('api/users/update-password', [
                'password' => $newPassword,
                'confirm_password' => $newPassword
            ]);

        $response->assertStatus(200);
    }
}

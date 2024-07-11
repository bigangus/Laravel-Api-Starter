<?php

namespace Database\Seeders;

use App\Models\System\Config;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::firstOrCreate(
            ['key' => 'password_pattern'],
            ['value' => '/^.{6,}$/']
        );

        Config::firstOrCreate(
            ['key' => 'token_expires_in'],
            ['value' => 7]
        );

        Config::firstOrCreate(
            ['key' => 'single_session'],
            ['value' => false]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\System\Config;
use App\Models\System\Dictionary;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passwordPatterns = json_decode(Dictionary::where('key', 'password_patterns')->first()->value, true);

        Config::firstOrCreate(
            ['key' => 'password_pattern'],
            ['value' => $passwordPatterns['easy']]
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

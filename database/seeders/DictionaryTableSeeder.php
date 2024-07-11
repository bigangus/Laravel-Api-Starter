<?php

namespace Database\Seeders;

use App\Models\System\Dictionary;
use Illuminate\Database\Seeder;

class DictionaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dictionary::firstOrCreate(
            ['key' => 'password_patterns'],
            [
                'name' => 'Password Patterns',
                'value' => json_encode([
                    'easy' => '/^.{6,}$/',
                    'medium' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                    'strong' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{12,}$/',
                ]),
            ]
        );
    }
}

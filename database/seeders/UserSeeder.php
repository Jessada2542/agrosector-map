<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'DEV DIW',
            'username' => 'dev-diw',
            'password' => Hash::make('dev-diw'),
            'role_id' => 3
        ];

        User::create($data);
    }
}

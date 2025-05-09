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
            'name' => 'SPTI',
            'username' => 'SPTI',
            'password' => Hash::make('pcru67000'),
            'role_id' => 2
        ];

        User::create($data);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'User'],
            ['name' => 'Admin'],
            ['name' => 'Dev']
        ];

        foreach ($data as $value) {
            Role::create($value);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mimin',
            'email' => 'admin@bwa.com',
            'password' => '12345',
            'phone_number' => '085296995799',
            'avatar' => '',
            'role' => 'admin',

        ]);
    }
}

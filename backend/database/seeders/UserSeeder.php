<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin GSG',
            'email' => 'admin@gsg.co.id',
            'password' => Hash::make('GSG-Admin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

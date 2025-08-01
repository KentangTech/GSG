<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin GSG',
            'email' => 'admin@gsg.co.id',
            'password' => Hash::make('GrahaSarana75'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

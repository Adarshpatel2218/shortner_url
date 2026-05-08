<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $adarshId = DB::table('users')->insertGetId([
            'name' => 'Adarsh',
            'email' => 'adarsh@gmail.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rahulId = DB::table('users')->insertGetId([
            'name' => 'Rahul',
            'email' => 'rahul@gmail.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sevenId = DB::table('companies')->insertGetId([
            'name' => 'sevenunique',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $finId = DB::table('companies')->insertGetId([
            'name' => 'finunique',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('company_user')->insert([
            [
                'user_id' => $adarshId,
                'company_id' => $sevenId,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adarshId,
                'company_id' => $finId,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $rahulId,
                'company_id' => $sevenId,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
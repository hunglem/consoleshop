<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([

            [
                'name'=> 'Hung',
                'email'=> 'Hung@email.com',
                'password'=> Hash::make('123'),
                'role'=> 'admin',
            ],

            [
                'name'=> 'Admin',
                'email'=> 'Admin@email.com',
                'password'=> Hash::make('123'),
                'role'=> 'admin',
            ],

            [
                'name'=> 'New',
                'email'=> 'New@email.com',
                'password'=> Hash::make('777'),
                'role'=> 'admin',
            ],

            [
                'name'=> 'gess',
                'email'=> '1@email.com',
                'password'=> Hash::make('777'),
                'role'=> 'user',
            ]

        ]);
    }
}

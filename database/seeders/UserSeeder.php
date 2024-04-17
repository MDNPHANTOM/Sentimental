<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'isAdmin' => 1,
            'concerns' => 0,
            'reported' => 0,
            'support_Tier' => 0,
            'blocked' => 0,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'isAdmin' => 0,
            'concerns' => 0,
            'reported' => 0,
            'support_Tier' => 0,
            'blocked' => 0,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        
        User::factory()->create([
            'name' => 'Blocked',
            'email' => 'blocked@example.com',
            'isAdmin' => 0,
            'concerns' => 0,
            'reported' => 0,
            'support_Tier' => 0,
            'blocked' => 1,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        
        User::factory()->count(10)->create();
    }
}

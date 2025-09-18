<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Rowel',
            'middle_name' => 'Duma',
            'last_name' => 'Pangue',
            'role' => 'admin',
            'email' => 'rcdpangue@gmail.com',
            'password' => Hash::make('test123'),
        ]);
    }
}

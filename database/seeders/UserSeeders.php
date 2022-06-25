<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'manager',
            'name' => 'manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('password'),
        ]);
    }
}

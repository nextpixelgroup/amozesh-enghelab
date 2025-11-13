<?php

namespace Database\Seeders;

use App\Enums\DegreeEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'hosseinhezareh',
            'firstname' => 'Hossein',
            'lastname' => 'Hezareh',
            'password' => Hash::make('123456'),
            'email' => 'hosseinhezareh2@gmail.com',
        ]);

        $user->assignRole('super-admin');

        $user = User::create([
            'firstname' => 'حسین',
            'lastname' => 'معلمیان',
            'password' => Hash::make('123456'),
            'email' => 'teacher@gmail.com',
        ]);
        $user->assignRole('teacher');
    }
}

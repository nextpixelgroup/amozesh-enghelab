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
            'firstname' => 'Hossein',
            'lastname' => 'Hezareh',
            'password' => Hash::make('123456'),
            'email' => 'hosseinhezareh2@gmail.com',
        ]);

        $degrees = enumNames(DegreeEnum::cases());
        $startDate = fake()->dateTimeBetween('-10 years', '-1 year');
        $isCurrentlyStudying = fake()->boolean(30); // 30% شانس در حال تحصیل بودن

        $user->educationals()->create([
            'institution' => fake()->company() . ' University',
            'field_of_study' => fake()->jobTitle() . ' Engineering',
            'degree' => fake()->randomElement($degrees),
            'start_date' => $startDate,
            'end_date' => $isCurrentlyStudying ? null : fake()->dateTimeBetween($startDate, 'now'),
            'is_currently_studying' => $isCurrentlyStudying,
            'description' => fake()->sentence(),
        ]);
        $user->assignRole('super-admin');

        $user = User::create([
            'firstname' => 'teacher',
            'lastname' => 'teacher',
            'password' => Hash::make('123456'),
            'email' => 'teacher@gmail.com',
        ]);
        $user->assignRole('teacher');
    }
}

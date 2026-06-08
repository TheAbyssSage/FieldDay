<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherRole = Role::where('name', 'teacher')->first();
        $guardianRole = Role::where('name', 'guardian')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@fieldday.test'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create 10 teachers
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => "teacher{$i}@fieldday.test"],
                [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone_number' => fake()->phoneNumber(),
                    'password' => Hash::make('password'),
                    'role_id' => $teacherRole->id,
                ]
            );
        }

        // Create 20 guardians
        for ($i = 1; $i <= 20; $i++) {
            User::firstOrCreate(
                ['email' => "guardian{$i}@fieldday.test"],
                [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone_number' => fake()->phoneNumber(),
                    'password' => Hash::make('password'),
                    'role_id' => $guardianRole->id,
                ]
            );
        }
    }
}

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
        $adminUser = User::withTrashed()->updateOrCreate(
            ['email' => 'admin@fieldday.test'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );
        if ($adminUser->trashed()) {
            $adminUser->restore();
        }

        // Create Sage admin user
        $sageAdmin = User::withTrashed()->updateOrCreate(
            ['email' => 'sage.stockmans@pm.me'],
            [
                'first_name' => 'Sage',
                'last_name' => '',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole->id,
            ]
        );
        if ($sageAdmin->trashed()) {
            $sageAdmin->restore();
        }

        // Create Sage teacher user
        $sageTeacher = User::withTrashed()->updateOrCreate(
            ['email' => 'teacher.sage@pm.me'],
            [
                'first_name' => 'Sage',
                'last_name' => '',
                'password' => Hash::make('12345678'),
                'role_id' => $teacherRole->id,
            ]
        );
        if ($sageTeacher->trashed()) {
            $sageTeacher->restore();
        }

        // Create 10 teachers
        for ($i = 1; $i <= 10; $i++) {
            $teacher = User::withTrashed()->updateOrCreate(
                ['email' => "teacher{$i}@fieldday.test"],
                [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone_number' => fake()->phoneNumber(),
                    'password' => Hash::make('password'),
                    'role_id' => $teacherRole->id,
                ]
            );
            if ($teacher->trashed()) {
                $teacher->restore();
            }
        }

        // Create 20 guardians
        for ($i = 1; $i <= 20; $i++) {
            $guardian = User::withTrashed()->updateOrCreate(
                ['email' => "guardian{$i}@fieldday.test"],
                [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'phone_number' => fake()->phoneNumber(),
                    'password' => Hash::make('password'),
                    'role_id' => $guardianRole->id,
                ]
            );
            if ($guardian->trashed()) {
                $guardian->restore();
            }
        }
    }
}

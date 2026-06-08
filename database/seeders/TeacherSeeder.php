<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'teacher')->first();

        for ($i = 0; $i < 10; $i++) {
            Teacher::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
                'password' => bcrypt('password'),
                'role_id' => $role->id,
            ]);
        }
    }
}

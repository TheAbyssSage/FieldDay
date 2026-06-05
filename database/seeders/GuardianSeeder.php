<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GuardianSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'guardian')->first();

        for ($i = 0; $i < 30; $i++) {
            Guardian::create([
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

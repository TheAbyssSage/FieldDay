<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuardianSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'guardian')->firstOrFail();

        // A known login for development and demos.
        User::firstOrCreate(
            ['email' => 'guardian@fieldday.test'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Guardian',
                'phone_number' => fake()->phoneNumber(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ],
        );

        User::factory()
            ->count(49)
            ->create(['role_id' => $role->id]);
    }
}

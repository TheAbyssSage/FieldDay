<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        User::firstOrCreate(
            ['email' => 'admin@fieldday.test'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'phone_number' => fake()->phoneNumber(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ],
        );
    }
}

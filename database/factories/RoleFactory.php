<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['admin', 'teacher', 'guardian']),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['name' => 'admin']);
    }

    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => ['name' => 'teacher']);
    }

    public function guardian(): static
    {
        return $this->state(fn (array $attributes) => ['name' => 'guardian']);
    }
}

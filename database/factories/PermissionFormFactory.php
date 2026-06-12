<?php

namespace Database\Factories;

use App\Models\PermissionForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PermissionForm>
 */
class PermissionFormFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'user_id' => User::factory(),
        ];
    }
}

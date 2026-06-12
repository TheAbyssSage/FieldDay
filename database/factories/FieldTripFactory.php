<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\FieldTrip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FieldTrip>
 */
class FieldTripFactory extends Factory
{
    public function definition(): array
    {
        $beginDate = fake()->dateTimeBetween('+1 week', '+2 months');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'begin_date' => $beginDate->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween($beginDate, '+3 months')->format('Y-m-d'),
            'departure_time' => fake()->dateTimeBetween($beginDate, $beginDate->modify('+1 hour')),
            'return_time' => fake()->dateTimeBetween($beginDate->modify('+2 hours'), $beginDate->modify('+8 hours')),
            'cost' => fake()->optional()->randomFloat(2, 0, 100),
            'payment_deadline' => fake()->optional()->date('Y-m-d', $beginDate->format('Y-m-d')),
            'classroom_id' => Classroom::factory(),
            'status' => 'open',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'completed']);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelled']);
    }

    public function withCost(float $cost): static
    {
        return $this->state(fn (array $attributes) => ['cost' => $cost]);
    }
}

<?php

namespace Database\Factories;

use App\Models\FieldTrip;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'student_id' => Student::factory(),
            'field_trip_id' => FieldTrip::factory(),
            'amount' => fake()->randomFloat(2, 5, 50),
            'status' => 'pending',
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'paid_at' => now(),
        ]);
    }
}

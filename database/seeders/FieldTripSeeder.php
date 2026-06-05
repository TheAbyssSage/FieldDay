<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\FieldTrip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldTripSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {
            $beginDate = fake()->dateTimeBetween('+1 week', '+3 months');
            $endDate = (clone $beginDate)->modify('+'.fake()->numberBetween(0, 3).' days');

            FieldTrip::create([
                'title' => fake()->sentence(3),
                'description' => fake()->paragraph(),
                'location' => fake()->city(),
                'begin_date' => $beginDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'departure_time' => fake()->dateTimeBetween('06:00', '10:00'),
                'return_time' => fake()->dateTimeBetween('14:00', '18:00'),
                'cost' => fake()->randomFloat(2, 10, 200),
                'payment_deadline' => fake()->dateTimeBetween('now', $beginDate)->format('Y-m-d'),
                'classroom_id' => $classroom->id,
                'status' => fake()->randomElement(['open', 'completed', 'cancelled']),
            ]);
        }
    }
}

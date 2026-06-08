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
            for ($i = 0; $i < fake()->numberBetween(3, 5); $i++) {
                $beginDate = fake()->dateTimeBetween('+1 week', '+6 months');
                $endDate = (clone $beginDate)->modify('+'.fake()->numberBetween(0, 3).' days');

                FieldTrip::firstOrCreate(
                    [
                        'title' => fake()->sentence(3),
                        'classroom_id' => $classroom->id,
                    ],
                    [
                        'description' => fake()->paragraph(),
                        'location' => fake()->city(),
                        'begin_date' => $beginDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'departure_time' => fake()->dateTimeBetween('06:00', '10:00'),
                        'return_time' => fake()->dateTimeBetween('14:00', '18:00'),
                        'cost' => fake()->randomFloat(2, 10, 200),
                        'payment_deadline' => fake()->dateTimeBetween('now', $beginDate)->format('Y-m-d'),
                        'status' => fake()->randomElement(['open', 'completed', 'cancelled']),
                    ]
                );
            }
        }
    }
}

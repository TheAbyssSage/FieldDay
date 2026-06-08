<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = Classroom::all();
        $guardians = Guardian::all();

        foreach ($classrooms as $classroom) {
            for ($i = 0; $i < fake()->numberBetween(5, 40); $i++) {
                $student = Student::create([
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'date_of_birth' => fake()->dateTimeBetween('-14 years', '-5 years')->format('Y-m-d'),
                    'classroom_id' => $classroom->id,
                ]);

                $student->guardians()->attach(
                    $guardians->random(fake()->numberBetween(1, 2))->pluck('id')
                );
            }
        }
    }
}

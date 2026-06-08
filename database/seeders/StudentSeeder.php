<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
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
        $guardians = User::whereHas('role', function ($query) {
            $query->where('name', 'guardian');
        })->get();

        foreach ($classrooms as $classroom) {
            for ($i = 0; $i < fake()->numberBetween(5, 40); $i++) {
                $student = Student::firstOrCreate(
                    [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'classroom_id' => $classroom->id,
                    ],
                    [
                        'date_of_birth' => fake()->dateTimeBetween('-14 years', '-5 years')->format('Y-m-d'),
                    ]
                );

                try {
                    $student->users()->syncWithoutDetaching(
                        $guardians->random(fake()->numberBetween(1, 2))->pluck('id')->unique()->values()
                    );
                } catch (UniqueConstraintViolationException) {
                    // Already linked, skip
                }
            }
        }
    }
}

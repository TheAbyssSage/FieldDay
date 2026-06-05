<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            Classroom::create([
                'name' => fake()->unique()->randomElement(['A', 'B', 'C', 'D', 'E']) . fake()->numberBetween(1, 9),
                'teacher_id' => $teacher->id,
            ]);
        }
    }
}

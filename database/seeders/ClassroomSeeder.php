<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
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
        $teachers = User::whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        foreach ($teachers as $i => $teacher) {
            // intdiv($i, 5) — integer division, no decimals
            // +1 so grades start at 1 instead of 0
            $grade = intdiv($i, 5) + 1;

            // chr(65 + ($i % 5)) — converts a number to a letter
            // 65 is the ASCII code for 'A', 66 = 'B', 67 = 'C', etc.
            $section = chr(65 + ($i % 5));

            Classroom::firstOrCreate(
                ['name' => "Grade-{$grade}{$section}"],
                ['user_id' => $teacher->id]
            );
        }
    }
}

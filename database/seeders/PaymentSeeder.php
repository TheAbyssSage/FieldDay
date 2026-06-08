<?php

namespace Database\Seeders;

use App\Models\FieldTrip;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('users')->get();
        $fieldTrips = FieldTrip::all();

        foreach ($students as $student) {
            foreach ($student->users as $guardian) {
                foreach ($fieldTrips->random(fake()->numberBetween(1, 3)) as $fieldTrip) {
                    $status = fake()->randomElement(['pending', 'paid', 'refunded']);

                    Payment::firstOrCreate(
                        [
                            'user_id' => $guardian->id,
                            'student_id' => $student->id,
                            'field_trip_id' => $fieldTrip->id,
                        ],
                        [
                            'amount' => $fieldTrip->cost,
                            'status' => $status,
                            'paid_at' => $status === 'paid' || $status === 'refunded' ? fake()->dateTimeBetween('-1 month', 'now') : null,
                        ]
                    );
                }
            }
        }
    }
}

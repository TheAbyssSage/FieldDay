<?php

namespace App\Livewire\Guardian;

use App\Models\FieldTrip;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Guardian dashboard — shows trips for the students linked to this guardian,
 * along with payment status per student per trip.
 */
class Dashboard extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch all students linked to this guardian (via guardian_student pivot)
        $students = $user->students()->with('classroom')->get();

        // Collect unique classroom IDs from these students
        $classroomIds = $students->pluck('classroom_id')->unique()->filter();

        // Fetch all trips for those classrooms
        $trips = FieldTrip::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->latest()
            ->get();

        // Existing payments by this guardian, keyed by "student_id-trip_id" for fast lookup in the view
        $payments = Payment::where('user_id', $user->id)
            ->whereIn('field_trip_id', $trips->pluck('id'))
            ->get()
            ->keyBy(function ($payment) {
                return $payment->student_id.'-'.$payment->field_trip_id;
            });

        // Count pending and paid payments for the summary cards
        $pendingCount = $payments->where('status', Payment::STATUS_PENDING)->count();
        $paidCount = $payments->where('status', Payment::STATUS_PAID)->count();

        return view('livewire.guardian.dashboard', [
            'students' => $students,
            'trips' => $trips,
            'payments' => $payments,
            'pendingCount' => $pendingCount,
            'paidCount' => $paidCount,
        ]);
    }
}

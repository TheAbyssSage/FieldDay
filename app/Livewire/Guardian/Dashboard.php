<?php

namespace App\Livewire\Guardian;

use App\Models\FieldTrip;
use App\Models\Payment;
use App\Models\Student;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Guardian dashboard — shows trips for the students linked to this guardian,
 * along with payment status per student per trip.
 */
class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch all students linked to this guardian (via guardian_student pivot)
        $students = $user->students()->with('classroom')->get();

        // Collect unique classroom IDs from these students
        $classroomIds = $students->pluck('classroom_id')->unique()->filter();

        // Fetch ALL trips (unpaginated) for counting and payment lookup
        $allTrips = FieldTrip::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->latest()
            ->get();

        // Fetch paginated trips for the table display (10 per page)
        $trips = FieldTrip::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->latest()
            ->paginate(10);

        // Existing payments by this guardian across ALL trips, keyed by "student_id-trip_id"
        $payments = Payment::where('user_id', $user->id)
            ->whereIn('field_trip_id', $allTrips->pluck('id'))
            ->get()
            ->keyBy(function ($payment) {
                return $payment->student_id.'-'.$payment->field_trip_id;
            });

        // Count pending/paid across ALL student×trip combos, not just the current page
        $pendingCount = 0;
        $paidCount = 0;

        foreach ($allTrips as $trip) {
            foreach ($students->where('classroom_id', $trip->classroom_id) as $student) {
                $payment = $payments->get($student->id.'-'.$trip->id);

                if ($payment && $payment->status === Payment::STATUS_PAID) {
                    $paidCount++;
                } elseif ($payment && $payment->status === Payment::STATUS_PENDING) {
                    $pendingCount++;
                } else {
                    // No payment record yet — counts as pending (needs action)
                    $pendingCount++;
                }
            }
        }

        return view('livewire.guardian.dashboard', [
            'students' => $students,
            'trips' => $trips,
            'payments' => $payments,
            'pendingCount' => $pendingCount,
            'paidCount' => $paidCount,
        ]);
    }

    /**
     * Mark a payment as paid for a specific student on a specific trip.
     * Creates the payment record if it doesn't exist yet (firstOrCreate).
     * Also serves as parental permission confirmation for free trips.
     */
    public function pay(FieldTrip $trip, Student $student): void
    {
        // Only allow payment for trips that are still open
        if ($trip->status !== 'open') {
            Flux::toast(variant: 'error', text: 'This trip is no longer open for payments.');

            return;
        }

        // Find existing payment or create a new one with pending status
        $payment = Payment::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'student_id' => $student->id,
                'field_trip_id' => $trip->id,
            ],
            [
                'amount' => $trip->cost ?? 0,
                'status' => Payment::STATUS_PENDING,
            ],
        );

        // Mark as paid with current timestamp
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        Flux::toast(variant: 'success', text: "Payment marked as paid for {$student->first_name} — {$trip->title}.");
    }
}

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

        // Fetch ALL trips for these classrooms (unpaginated) — used for counting and payment lookup
        $allTrips = FieldTrip::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->latest()
            ->get();

        // Paginated slice for the table display (10 per page)
        $trips = FieldTrip::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->latest()
            ->paginate(10);

        // All trip IDs for payment lookup
        $allTripIds = $allTrips->pluck('id');

        // Existing payments by this guardian, keyed by "student_id-trip_id" for O(1) lookup in the view
        $payments = Payment::where('user_id', $user->id)
            ->whereIn('field_trip_id', $allTripIds)
            ->get()
            ->keyBy(fn ($p) => $p->student_id.'-'.$p->field_trip_id);

        // Count total student×trip combinations across ALL trips
        $studentsByClassroom = $students->groupBy('classroom_id');
        $totalCombos = 0;
        foreach ($allTrips as $trip) {
            $totalCombos += ($studentsByClassroom->get($trip->classroom_id)?->count() ?? 0);
        }

        // Count paid payments with a single DB query
        $paidCount = Payment::where('user_id', $user->id)
            ->whereIn('field_trip_id', $allTripIds)
            ->where('status', Payment::STATUS_PAID)
            ->count();

        // Everything not paid is pending (including combos with no payment record yet)
        $pendingCount = $totalCombos - $paidCount;

        return view('livewire.guardian.dashboard', [
            'students'          => $students,
            'studentsByClassroom' => $studentsByClassroom,
            'trips'             => $trips,
            'payments'          => $payments,
            'pendingCount'      => $pendingCount,
            'paidCount'         => $paidCount,
        ]);
    }

    /**
     * Mark a payment as paid for a specific student on a specific trip.
     * Creates the payment record if it doesn't exist yet (firstOrCreate).
     * Also serves as parental permission confirmation for free trips.
     */
    public function pay(FieldTrip $trip, Student $student): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify this student belongs to the authenticated guardian
        if (! $user->students()->where('student_id', $student->id)->exists()) {
            Flux::toast(variant: 'error', text: 'This student is not linked to your account.');

            return;
        }

        // Only allow payment for trips that are still open
        if ($trip->status !== 'open') {
            Flux::toast(variant: 'error', text: 'This trip is no longer open for payments.');

            return;
        }

        // Prevent re-paying an already paid or refunded payment
        $existing = Payment::where('user_id', Auth::id())
            ->where('student_id', $student->id)
            ->where('field_trip_id', $trip->id)
            ->first();

        if ($existing && in_array($existing->status, [Payment::STATUS_PAID, Payment::STATUS_REFUNDED], true)) {
            Flux::toast(variant: 'error', text: 'This payment has already been processed.');

            return;
        }

        // Find existing payment or create a new one with pending status
        $payment = Payment::firstOrCreate(
            [
                'user_id'       => Auth::id(),
                'student_id'    => $student->id,
                'field_trip_id' => $trip->id,
            ],
            [
                'amount' => $trip->cost ?? 0,
                'status' => Payment::STATUS_PENDING,
            ],
        );

        // Mark as paid with current timestamp
        $payment->update([
            'status'  => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        Flux::toast(variant: 'success', text: "Payment marked as paid for {$student->first_name} — {$trip->title}.");
    }
}

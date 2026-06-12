<?php

namespace App\Livewire\Teacher\Trips;

use App\Models\FieldTrip;
use App\Models\PermissionFormSignature;
use App\Models\Payment;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public FieldTrip $trip;

    public function mount(FieldTrip $trip): void
    {
        if ($trip->classroom->user_id !== Auth::id()) {
            abort(403);
        }

        $this->trip = $trip->load('classroom.students.users', 'permissionForm');
    }

    public function complete(): void
    {
        $this->trip->update(['status' => FieldTrip::STATUSES[1]]); // 'completed'
        Flux::toast(variant: 'success', text: 'Trip marked as completed.');
        $this->trip->refresh();
    }

    public function cancel(): void
    {
        $this->trip->update(['status' => FieldTrip::STATUS_CANCELLED]);
        Flux::toast(variant: 'success', text: 'Trip cancelled.');
        $this->trip->refresh();
    }

    public function delete(): void
    {
        $this->trip->delete();
        Flux::toast(variant: 'success', text: 'Trip deleted.');
        $this->redirectRoute('teacher.trips.index', navigate: true);
    }

    public function markPaid(int $studentId): void
    {
        $payment = Payment::firstOrNew([
            'field_trip_id' => $this->trip->id,
            'student_id' => $studentId,
        ]);

        $payment->fill([
            'user_id' => Auth::id(),
            'amount' => $this->trip->cost ?? 0,
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ])->save();

        Flux::toast(variant: 'success', text: 'Payment marked as paid.');
        $this->trip->refresh();
    }

    public function markUnpaid(int $studentId): void
    {
        Payment::where('field_trip_id', $this->trip->id)
            ->where('student_id', $studentId)
            ->update(['status' => Payment::STATUS_PENDING, 'paid_at' => null]);

        Flux::toast(variant: 'success', text: 'Payment marked as pending.');
        $this->trip->refresh();
    }

    public function markSigned(int $studentId, int $guardianId): void
    {
        if (! $this->trip->permission_form_id) {
            Flux::toast(variant: 'error', text: 'No permission form attached to this trip.');
            return;
        }

        PermissionFormSignature::firstOrCreate([
            'permission_form_id' => $this->trip->permission_form_id,
            'student_id' => $studentId,
            'user_id' => $guardianId,
        ], [
            'signed_at' => now(),
        ]);

        Flux::toast(variant: 'success', text: 'Form marked as signed.');
        $this->trip->refresh();
    }

    public function markUnsigned(int $studentId): void
    {
        PermissionFormSignature::where('permission_form_id', $this->trip->permission_form_id)
            ->where('student_id', $studentId)
            ->delete();

        Flux::toast(variant: 'success', text: 'Form signature removed.');
        $this->trip->refresh();
    }

    public function render()
    {
        $students = $this->trip->classroom->students;

        $payments = Payment::where('field_trip_id', $this->trip->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $signatures = collect();
        if ($this->trip->permission_form_id) {
            $signatures = PermissionFormSignature::where('permission_form_id', $this->trip->permission_form_id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_id');
        }

        return view('livewire.teacher.trips.show', [
            'students' => $students,
            'payments' => $payments,
            'signatures' => $signatures,
        ]);
    }
}

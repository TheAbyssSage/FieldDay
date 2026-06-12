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

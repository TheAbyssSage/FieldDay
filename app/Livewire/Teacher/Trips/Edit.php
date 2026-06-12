<?php

namespace App\Livewire\Teacher\Trips;

use App\Models\Classroom;
use App\Models\FieldTrip;
use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public FieldTrip $trip;
    public string $title = '';
    public string $description = '';
    public string $location = '';
    public ?int $classroom_id = null;
    public ?int $permission_form_id = null;
    public string $begin_date = '';
    public string $end_date = '';
    public string $departure_time = '';
    public string $return_time = '';
    public ?string $cost = null;
    public ?string $payment_deadline = null;
    public string $status = '';

    public function mount(FieldTrip $trip): void
    {
        if ($trip->classroom->user_id !== Auth::id()) {
            abort(403);
        }

        $this->trip = $trip;
        $this->title = $trip->title;
        $this->description = $trip->description;
        $this->location = $trip->location;
        $this->classroom_id = $trip->classroom_id;
        $this->permission_form_id = $trip->permission_form_id;
        $this->begin_date = $trip->begin_date->format('Y-m-d');
        $this->end_date = $trip->end_date->format('Y-m-d');
        $this->departure_time = $trip->departure_time->format('Y-m-d\TH:i');
        $this->return_time = $trip->return_time->format('Y-m-d\TH:i');
        $this->cost = $trip->cost;
        $this->payment_deadline = $trip->payment_deadline?->format('Y-m-d');
        $this->status = $trip->status;
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'permission_form_id' => ['nullable', 'exists:permission_forms,id'],
            'begin_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:begin_date'],
            'departure_time' => ['required', 'date'],
            'return_time' => ['required', 'date', 'after:departure_time'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'payment_deadline' => ['nullable', 'date', 'before_or_equal:begin_date'],
            'status' => ['required', Rule::in(FieldTrip::STATUSES)],
        ];
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->trip->update($validated);

        session()->flash('status', 'Trip updated successfully.');

        $this->redirectRoute('teacher.trips.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.teacher.trips.edit', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'permissionForms' => PermissionForm::where('user_id', Auth::id())->orderBy('title')->get(),
            'statuses' => FieldTrip::STATUSES,
        ]);
    }
}

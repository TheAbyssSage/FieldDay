<?php

namespace App\Livewire\Teacher\Trips;

use App\Models\Classroom;
use App\Models\FieldTrip;
use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
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
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        FieldTrip::create($validated);

        session()->flash('status', 'Trip created successfully.');

        $this->redirectRoute('new-trip.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.teacher.trips.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'permissionForms' => PermissionForm::where('user_id', Auth::id())->orderBy('title')->get(),
        ]);
    }
}

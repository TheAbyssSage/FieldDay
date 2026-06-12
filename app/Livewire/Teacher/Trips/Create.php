<?php

namespace App\Livewire\Teacher\Trips;

use App\Models\Classroom;
use App\Models\FieldTrip;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $description = '';
    public string $location = '';
    public ?int $classroom_id = null;
    public string $begin_date = '';
    public string $end_date = '';
    public string $departure_time = '';
    public string $return_time = '';
    public string $cost = '';
    public string $payment_deadline = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'begin_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:begin_date'],
            'departure_time' => ['required', 'date'],
            'return_time' => ['required', 'date', 'after:departure_time'],
            'cost' => ['required', 'numeric', 'min:0'],
            'payment_deadline' => ['required', 'date', 'before_or_equal:begin_date'],
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
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Trips;

use App\Models\Classroom;
use App\Models\FieldTrip;
use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
// livewire gebruiken om een trip te bewerken in het admin panel
class Edit extends Component
{
    // de trip die we willen bewerken
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
    // de trip die we willen bewerken in de mount functie zetten
    public function mount(FieldTrip $trip): void
    {
        if (! Auth::user()?->hasRole('admin')) {
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
    // de regels voor het valideren van de input
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
    // de update functie die wordt aangeroepen wanneer de gebruiker op de update knop klikt
    public function update(): void
    {
        $validated = $this->validate();

        $this->trip->update($validated);
        // een flash message tonen dat de trip succesvol is bijgewerkt
        session()->flash('status', 'Trip updated successfully.');

        $this->redirectRoute('admin.trips', navigate: true);
    }
    // de render functie die de view teruggeeft met de nodige data voor de dropdowns en selecties
    public function render()
    {   // de classrooms en statussen ophalen en doorgeven aan de view
        return view('livewire.admin.trips.edit', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'permissionForms' => PermissionForm::orderBy('title')->get(),
            'statuses' => FieldTrip::STATUSES,
        ]);
    }
}
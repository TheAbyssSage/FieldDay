<?php

namespace App\Livewire\Admin\Trips;

use App\Models\Classroom;
use App\Models\FieldTrip;
use Carbon\Carbon;
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
    public string $begin_date = '';
    public string $end_date = '';
    public string $departure_time = '';
    public string $return_time = '';
    public string $cost = '';
    public string $payment_deadline = '';
    public string $status = '';
    // de trip die we willen bewerken in de mount functie zetten
    public function mount(FieldTrip $trip): void
    {
        $this->trip = $trip;
        $this->title = $trip->title;
        $this->description = $trip->description;
        $this->location = $trip->location;
        $this->classroom_id = $trip->classroom_id;
        $this->begin_date = $trip->begin_date->format('Y-m-d');
        $this->end_date = $trip->end_date->format('Y-m-d');
        $this->departure_time = $trip->departure_time->format('H:i');
        $this->return_time = $trip->return_time->format('H:i');
        $this->cost = $trip->cost;
        $this->payment_deadline = $trip->payment_deadline->format('Y-m-d');
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
            'begin_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:begin_date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'return_time' => ['required', 'date_format:H:i'],
            'cost' => ['required', 'numeric', 'min:0'],
            'payment_deadline' => ['required', 'date', 'before_or_equal:begin_date'],
            'status' => ['required', Rule::in(FieldTrip::STATUSES)],
        ];
    }
    // de update functie die wordt aangeroepen wanneer de gebruiker op de update knop klikt
    public function update(): void
    {
        // we valideren de input met de regels die we hebben gedefinieerd in de rules() functie.
        $validated = $this->validate();
        // Carbon gebruiken om de begin en eind datums en tijden samen te voegen tot datetime objecten.
        $departure = Carbon::parse("{$this->begin_date} {$this->departure_time}");
        // we controleren of de return tijd na de departure tijd is, zo niet, tonen we een foutmelding en stoppen we het opslaan van de trip.
        $return = Carbon::parse("{$this->end_date} {$this->return_time}");
        // we voegen de departure en return datetimes toe aan de validated data, zodat we ze kunnen opslaan in de database.
        if ($return->lessThanOrEqualTo($departure)) {
            $this->addError('return_time', 'The return must be after the departure.');

            return;
        }
        $validated['departure_time'] = $departure;
        $validated['return_time'] = $return;

        $this->trip->update($validated);

        session()->flash('status', 'Trip updated successfully.');

        $this->redirectRoute('admin.trips', navigate: true);
    }
    // render() toont de livewire component en geeft de nodige data door
    public function render()
    {
        return view('livewire.admin.trips.edit', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'statuses' => FieldTrip::STATUSES,
        ]);
    }
}
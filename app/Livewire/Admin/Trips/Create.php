<?php

namespace App\Livewire\Admin\Trips;
// carbon is een library voor het werken met datums en tijden in PHP
use Carbon\Carbon;
use App\Models\Classroom;
use App\Models\FieldTrip;
use Livewire\Component;
// deze component is verantwoordelijk voor het aanmaken van een nieuwe trip in de admin dashboard
class Create extends Component
{
    // deze public properties zijn de velden van het formulier, ze worden automatisch gevuld door Livewire wanneer de gebruiker input geeft
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
    // deze protected function rules() definieert de validatieregels voor het formulier, Livewire gebruikt deze regels om de input te valideren voordat de trip wordt opgeslagen
    protected function rules(): array
    {
        // de regels zorgen ervoor dat alle velden verplicht zijn, dat de data in het juiste formaat is, en dat de status een van de toegestane waardes is
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
        ];
    }
    // deze public function save() wordt aangeroepen wanneer de gebruiker het formulier indient, het valideert de input, maakt een nieuwe trip aan in de database, toont een succesbericht, en redirect de gebruiker terug naar de lijst van trips
    public function save(): void
    {
        // we valideren de input met de regels die we hebben gedifinieerd in de rules() functie.
        $validated = $this->validate();
        // Carbon gebruiken om de begin en eind datums en tijden samen te voegen tot datetime objecten.
        $departure = Carbon::parse("{$this->begin_date} {$this->departure_time}");
        // we controleren of de return tijd na de departure tijd is, zo niet, tonen we een foutmelding en stoppen we het opslaan van de trip.
        $return = Carbon::parse("{$this->end_date} {$this->return_time}");
        
        if ($return->lessThanOrEqualTo($departure)) {
            $this->addError('return_time', 'The return must be after the departure.');

            return;
        }
        // we voegen de departure en return datetimes toe aan de validated data, zodat we ze kunnen opslaan in de database.
        $validated['departure_time'] = $departure;
        $validated['return_time'] = $return;

        FieldTrip::create($validated);

        session()->flash('status', 'Trip created successfully.');

        $this->redirectRoute('admin.trips', navigate: true);
    }
    // deze public function render() toont de livewire component en geeft de benodigde data door, zoals de lijst van classrooms (voor de dropdown) en de mogelijke statussen (voor de status dropdown)
    public function render()
    {
        return view('livewire.admin.trips.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }
}
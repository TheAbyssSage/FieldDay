<?php
// deze component toont de lisjt van alle trips in de admin dashboard
namespace App\Livewire\Admin\Trips;

use App\Models\FieldTrip;
// flux gebruiken om de trips op te halen en te tonen zonder de pagina te hoeven herladen, en we kunnen ook gemakkelijk functies toevoegen voor het verwijderen of bewerken van trips in de toekomst.
use Flux\Flux;
// we gebruiken Livewire om deze component te maken, zodat we de trips kunnen ophalen en tonen zonder de pagina te hoeven herladen, en we kunnen ook gemakkelijk functies toevoegen voor het verwijderen of bewerken van trips in de toekomst.
use Livewire\Component;
// deze component toont de lisjt van alle trips in de admin dashboard
class Index extends Component
{
    // de delete functie die wordt aangeroepen wanneer de gebruiker op de delete knop klikt, het verwijdert de trip uit de database, en toont een succesbericht
        public function delete(FieldTrip $trip): void
    {
        // in plaats van de trip echt te verwijderen, gebruiken we soft deletes, zodat we de trip later nog kunnen herstellen als dat nodig is.
        $trip->delete();
        // flux gebruiken om een toast bericht te tonen dat de trip succesvol is verwijderd, we gebruiken variant success voor een groene kleur, en we geven de tekst van het bericht mee.
        Flux::toast(variant: 'success', text: 'Trip deleted.');
    }
    // de public function haalt alle trips uit de database met hun gerelateerde classroom (via eager loading)
    public function render()
    {
        // de return view toont de livewire component en geeft de trips door als data
        return view('livewire.admin.trips.index', [
            'trips' => FieldTrip::with('classroom')
                ->orderByDesc('begin_date')
                ->get(),
        ]);
    }
}
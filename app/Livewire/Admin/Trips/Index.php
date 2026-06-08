<?php

namespace App\Livewire\Admin\Trips;

use App\Models\FieldTrip;
use Livewire\Component;
// deze component toont de lisjt van alle trips in de admin dashboard
class Index extends Component
{
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
<?php

namespace App\Livewire\Teacher\Trips;

use App\Models\FieldTrip;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.teacher.trips.index', [
            'trips' => FieldTrip::whereHas('classroom', function ($query) {
                $query->where('user_id', Auth::id());
            })->with('classroom')->latest()->paginate(15),
        ]);
    }
}

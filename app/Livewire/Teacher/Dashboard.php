<?php

namespace App\Livewire\Teacher;

use App\Models\FieldTrip;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $trips = FieldTrip::whereHas('classroom', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('classroom')->latest()->get();

        return view('livewire.teacher.dashboard', [
            'trips' => $trips,
        ]);
    }
}

<?php

namespace App\Livewire\Teacher;

use App\Models\FieldTrip;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $trips = FieldTrip::whereHas('classroom', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('classroom')->latest()->paginate(15);

        $upcomingCount = $trips->where('status', 'open')->where('begin_date', '>=', now())->count();
        $pendingPayments = Payment::whereIn('field_trip_id', $trips->pluck('id'))
            ->where('status', Payment::STATUS_PENDING)
            ->count();

        return view('livewire.teacher.dashboard', [
            'trips' => $trips,
            'upcomingCount' => $upcomingCount,
            'pendingPayments' => $pendingPayments,
        ]);
    }
}

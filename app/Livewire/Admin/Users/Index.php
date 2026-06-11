<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => User::with('role')
                ->orderBy('first_name')
                ->get(),
        ]);
    }
}
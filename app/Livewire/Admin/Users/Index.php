<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Flux\Flux;
use Livewire\Component;

class Index extends Component
{
    public function delete(User $user): void
    {
        if ($user->is(auth()->user())) {
            Flux::toast(variant: 'danger', text: 'You cannot delete your own account.');

            return;
        }

        $user->delete();

        Flux::toast(variant: 'success', text: 'User deleted.');
    }

    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => User::with('role')
                ->orderBy('first_name')
                ->get(),
        ]);
    }
}
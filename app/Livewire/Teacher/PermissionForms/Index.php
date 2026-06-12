<?php

namespace App\Livewire\Teacher\PermissionForms;

use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.teacher.permission-forms.index', [
            'forms' => PermissionForm::where('user_id', Auth::id())->latest()->paginate(15),
        ]);
    }
}

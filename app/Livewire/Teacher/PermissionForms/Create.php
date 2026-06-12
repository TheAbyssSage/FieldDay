<?php

namespace App\Livewire\Teacher\PermissionForms;

use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $description = '';
    public string $content = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['user_id'] = Auth::id();

        PermissionForm::create($validated);

        session()->flash('status', 'Permission form created successfully.');

        $this->redirectRoute('teacher.permission-forms.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.teacher.permission-forms.create');
    }
}

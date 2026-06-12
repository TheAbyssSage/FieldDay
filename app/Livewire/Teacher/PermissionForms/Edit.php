<?php

namespace App\Livewire\Teacher\PermissionForms;

use App\Models\PermissionForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public PermissionForm $form;

    public string $title = '';
    public string $description = '';
    public string $content = '';

    public function mount(PermissionForm $form): void
    {
        if ($form->user_id !== Auth::id()) {
            abort(403);
        }

        $this->form = $form;
        $this->title = $form->title;
        $this->description = $form->description;
        $this->content = $form->content;
    }

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

        $this->form->update($validated);

        session()->flash('status', 'Permission form updated successfully.');

        $this->redirectRoute('teacher.permission-forms.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.teacher.permission-forms.edit');
    }
}

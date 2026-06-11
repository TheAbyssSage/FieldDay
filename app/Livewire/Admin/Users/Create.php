<?php

namespace App\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $password = '';
    public ?int $role_id = null;
    // de validation rules voor het aanmaken van nieuwe users, de regels zorgen ervoor dat de data geldig is voor dat ze gesaved worden in DB.
    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
    // de save methode die wordt aangeroepen wanneer de admin een nieuwe user aanmaakt, deze methode valideert de data en maakt nieuwe user aan.
    public function save(): void
    {
        $validated = $this->validate();

        $user = User::create($validated);
        $user->forceFill(['email_verified_at' => now()])->save();

        session()->flash('status', 'User created successfully.');

        $this->redirectRoute('admin.users', navigate: true);
    }
    // de render methode die de view teruggeeft voor het aanmaken van nieuwe users, deze view bevat een form waar de admin de data kan invullen voor de nieuwe user.
    public function render()
    {
        return view('livewire.admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
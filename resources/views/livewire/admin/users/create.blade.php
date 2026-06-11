<div class="p-4">
    <div class="mb-4 flex items-center justify-between">
        <flux:heading size="xl">New user</flux:heading>
        <flux:button :href="route('admin.users')" variant="ghost" wire:navigate>Back to users</flux:button>
    </div>

    <form wire:submit="save" class="flex max-w-2xl flex-col gap-4">
        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="first_name" label="First name" />
            <flux:input wire:model="last_name" label="Last name" />
        </div>

        <flux:input type="email" wire:model="email" label="Email" />
        <flux:input wire:model="phone_number" label="Phone number" />
        <flux:input type="password" wire:model="password" label="Password" />

        <flux:select wire:model="role_id" label="Role" placeholder="Choose a role...">
            @foreach ($roles as $role)
                <flux:select.option :value="$role->id">{{ ucfirst($role->name) }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">Create user</flux:button>
            <flux:button :href="route('admin.users')" variant="ghost" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>
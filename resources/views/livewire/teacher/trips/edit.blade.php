<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Edit Trip') }}</flux:heading>
        <flux:button :href="route('teacher.trips.index')" variant="ghost" wire:navigate>Back to trips</flux:button>
    </div>

    <form wire:submit="update" class="flex max-w-2xl flex-col gap-4">
        <flux:input wire:model="title" label="Title" />
        <flux:textarea wire:model="description" label="Description" />
        <flux:input wire:model="location" label="Location" />

        <flux:select wire:model="classroom_id" label="Classroom" placeholder="Choose a classroom...">
            @foreach ($classrooms as $classroom)
                <flux:select.option :value="$classroom->id">{{ $classroom->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model="permission_form_id" label="Permission Form" placeholder="Choose a permission form... (optional)">
            @foreach ($permissionForms as $form)
                <flux:select.option :value="$form->id">{{ $form->title }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="grid grid-cols-2 gap-4">
            <flux:input type="date" wire:model="begin_date" label="Begin date" />
            <flux:input type="date" wire:model="end_date" label="End date" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:input type="datetime-local" wire:model="departure_time" label="Departure time" />
            <flux:input type="datetime-local" wire:model="return_time" label="Return time" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:input type="number" step="0.01" wire:model="cost" label="Cost (€)" />
            <flux:input type="date" wire:model="payment_deadline" label="Payment deadline" />
        </div>

        <flux:select wire:model="status" label="Status">
            @foreach ($statuses as $statusOption)
                <flux:select.option :value="$statusOption">{{ ucfirst($statusOption) }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">Save changes</flux:button>
            <flux:button :href="route('teacher.trips.index')" variant="ghost" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <flux:heading size="xl">{{ __('New Trip') }}</flux:heading>

    <form wire:submit="save" class="flex max-w-2xl flex-col gap-4">
        <flux:input wire:model="title" label="Title" />
        <flux:textarea wire:model="description" label="Description" />
        <flux:input wire:model="location" label="Location" />

        <flux:select wire:model="classroom_id" label="Classroom" placeholder="Choose a classroom...">
            @foreach ($classrooms as $classroom)
                <flux:select.option :value="$classroom->id">{{ $classroom->name }}</flux:select.option>
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

        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">Create trip</flux:button>
            <flux:button :href="route('new-trip.index')" variant="ghost" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>

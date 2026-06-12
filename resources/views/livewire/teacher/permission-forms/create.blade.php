<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <flux:heading size="xl">{{ __('New Permission Form') }}</flux:heading>

    <form wire:submit="save" class="flex max-w-2xl flex-col gap-4">
        <flux:input wire:model="title" label="Title" />
        <flux:textarea wire:model="description" label="Description" />
        <flux:textarea wire:model="content" label="Form Content" rows="10" />

        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">Create form</flux:button>
            <flux:button :href="route('teacher.permission-forms.index')" variant="ghost" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Permission Forms') }}</flux:heading>
        <flux:button :href="route('teacher.permission-forms.create')" variant="primary" wire:navigate>
            {{ __('New Form') }}
        </flux:button>
    </div>

    @if ($forms->isEmpty())
        <flux:text>You haven't created any permission forms yet.</flux:text>
    @else
        <div class="grid gap-4">
            @foreach ($forms as $form)
                <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <flux:heading size="lg">{{ $form->title }}</flux:heading>
                        <div class="flex items-center gap-2">
                            <flux:button size="sm" :href="route('teacher.permission-forms.edit', $form)" variant="ghost" wire:navigate>
                                Edit
                            </flux:button>
                            <flux:text size="sm" class="text-zinc-500">
                                {{ $form->created_at->format('M j, Y') }}
                            </flux:text>
                        </div>
                    </div>
                    <flux:text class="mt-1">{{ $form->description }}</flux:text>
                </div>
            @endforeach
        </div>
    @endif
</div>

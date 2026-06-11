<div class="p-4">
    <div class="mb-4 flex items-center justify-between">
        <flux:heading size="xl">Field trips</flux:heading>
        <flux:button :href="route('admin.trips.create')" variant="primary" wire:navigate>New trip</flux:button>
    </div>
    <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 text-neutral-500 dark:border-neutral-700">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Classroom</th>
                    <th class="px-4 py-3">Location</th>
                    <th class="px-4 py-3">Begin date</th>
                    <th class="px-4 py-3">Cost</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse ($trips as $trip)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $trip->title }}</td>
                        <td class="px-4 py-3">{{ $trip->classroom?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $trip->location }}</td>
                        <td class="px-4 py-3">{{ $trip->begin_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">€{{ number_format($trip->cost, 2) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($trip->status) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <flux:button size="sm" :href="route('admin.trips.edit', $trip)" wire:navigate>Edit</flux:button>
                                <flux:button size="sm" variant="subtle" wire:click="refund({{ $trip->id }})" wire:confirm="Refund all paid payments and cancel this trip?" :disabled="$trip->paid_payments_count === 0">Refund</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $trip->id }})" wire:confirm="Are you sure you want to delete this trip?" :disabled="$trip->paid_payments_count > 0">Delete</flux:button>                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-neutral-500">No trips found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
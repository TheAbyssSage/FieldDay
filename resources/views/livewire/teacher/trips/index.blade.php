<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('My Trips') }}</flux:heading>
        <flux:button :href="route('new-trip.index')" variant="primary" wire:navigate>
            {{ __('New Trip') }}
        </flux:button>
    </div>

    @if ($trips->isEmpty())
        <flux:text>You haven't created any trips yet.</flux:text>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-4 py-3 font-medium">Title</th>
                        <th class="px-4 py-3 font-medium">Location</th>
                        <th class="px-4 py-3 font-medium">Classroom</th>
                        <th class="px-4 py-3 font-medium">Begin Date</th>
                        <th class="px-4 py-3 font-medium">End Date</th>
                        <th class="px-4 py-3 font-medium">Cost</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($trips as $trip)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $trip->title }}</td>
                            <td class="px-4 py-3">{{ $trip->location }}</td>
                            <td class="px-4 py-3">{{ $trip->classroom->name }}</td>
                            <td class="px-4 py-3">{{ $trip->begin_date->format('M j, Y') }}</td>
                            <td class="px-4 py-3">{{ $trip->end_date->format('M j, Y') }}</td>
                            <td class="px-4 py-3">{{ $trip->cost ? '€' . number_format($trip->cost, 2) : 'Free' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                    {{ $trip->status === 'open' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $trip->status === 'completed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $trip->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <flux:button size="sm" :href="route('teacher.trips.edit', $trip)" wire:navigate>Edit</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

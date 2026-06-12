<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <flux:heading size="xl">Welcome, {{ auth()->user()->name }}</flux:heading>

    {{-- Summary cards: compact stats --}}
    <div class="flex gap-4">
        <div class="rounded-lg border border-zinc-200 px-4 py-2 dark:border-zinc-700">
            <flux:text size="sm" class="text-zinc-500">My Students</flux:text>
            <flux:heading size="lg">{{ $students->count() }}</flux:heading>
        </div>
        <div class="rounded-lg border border-zinc-200 px-4 py-2 dark:border-zinc-700">
            <flux:text size="sm" class="text-zinc-500">Pending</flux:text>
            <flux:heading size="lg">{{ $pendingCount }}</flux:heading>
        </div>
        <div class="rounded-lg border border-zinc-200 px-4 py-2 dark:border-zinc-700">
            <flux:text size="sm" class="text-zinc-500">Paid</flux:text>
            <flux:heading size="lg">{{ $paidCount }}</flux:heading>
        </div>
    </div>

    <flux:heading size="lg" class="mt-4">{{ __('Upcoming Trips') }}</flux:heading>

    @if ($trips->isEmpty())
        <flux:text>No upcoming trips for your children.</flux:text>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-4 py-3 font-medium">Trip</th>
                        <th class="px-4 py-3 font-medium">Student</th>
                        <th class="px-4 py-3 font-medium">Classroom</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Cost</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    {{-- One row per student per trip: loop trips, then students in that trip's classroom --}}
                    @foreach ($trips as $trip)
                        @foreach ($studentsByClassroom->get($trip->classroom_id, collect()) as $student)
                            @php
                                $payment = $payments->get($student->id . '-' . $trip->id);
                            @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $trip->title }}</td>
                                <td class="px-4 py-3">{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td class="px-4 py-3">{{ $trip->classroom->name }}</td>
                                <td class="px-4 py-3">{{ $trip->begin_date->format('M j, Y') }}</td>
                                <td class="px-4 py-3">{{ $trip->cost ? '€' . number_format($trip->cost, 2) : 'Free' }}</td>
                                <td class="px-4 py-3">
                                    {{-- Payment status badge --}}
                                    @if ($payment && $payment->status === 'paid')
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                            Paid
                                        </span>
                                    @elseif ($payment && $payment->status === 'refunded')
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                            Refunded
                                        </span>
                                    @elseif ($payment && $payment->status === 'pending')
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                            Not paid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{-- Action column: depends on trip status and payment status --}}
                                    @if ($trip->status === 'open' && (! $payment || $payment->status !== 'paid'))
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            wire:click="pay({{ $trip->id }}, {{ $student->id }})"
                                            wire:confirm="Confirm payment for {{ $student->first_name }} — {{ $trip->title }}?"
                                        >
                                            Mark as Paid
                                        </flux:button>
                                    @elseif ($payment && $payment->status === 'paid')
                                        <flux:text size="sm" class="text-green-600 dark:text-green-400">Paid ✓</flux:text>
                                    @elseif ($payment && $payment->status === 'refunded')
                                        <flux:text size="sm" class="text-red-600 dark:text-red-400">Refunded</flux:text>
                                    @else
                                        <flux:text size="sm" class="text-zinc-500">Closed</flux:text>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $trips->links() }}
        </div>
    @endif
</div>

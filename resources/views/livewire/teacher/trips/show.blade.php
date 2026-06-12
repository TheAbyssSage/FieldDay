<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $trip->title }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $trip->location }} — {{ $trip->classroom->name }}</flux:text>
        </div>
        <div class="flex items-center gap-2">
            @if ($trip->status === 'open')
                <flux:button size="sm" variant="primary" wire:click="complete" wire:confirm="Mark this trip as completed?">Complete</flux:button>
                <flux:button size="sm" variant="subtle" wire:click="cancel" wire:confirm="Cancel this trip?">Cancel</flux:button>
            @endif
            <flux:button size="sm" variant="danger" wire:click="delete" wire:confirm="Are you sure you want to delete this trip?">Delete</flux:button>
            <flux:button :href="route('teacher.trips.index')" variant="ghost" wire:navigate>Back</flux:button>
        </div>
    </div>

    @if ($trip->permissionForm)
        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="lg">{{ __('Permission Form') }}: {{ $trip->permissionForm->title }}</flux:heading>
            <flux:text class="mt-1 text-zinc-500">{{ $trip->permissionForm->description }}</flux:text>
            <div class="mt-3 rounded-md bg-zinc-50 p-4 dark:bg-zinc-900">
                <p class="whitespace-pre-wrap text-sm">{{ $trip->permissionForm->content }}</p>
            </div>
        </div>
    @endif

    <flux:heading size="lg" class="mt-4">{{ __('Students') }}</flux:heading>

    @if ($students->isEmpty())
        <flux:text>No students in this classroom yet.</flux:text>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-4 py-3 font-medium">Student</th>
                        <th class="px-4 py-3 font-medium">Guardian</th>
                        <th class="px-4 py-3 font-medium">Payment</th>
                        <th class="px-4 py-3 font-medium">Form Signed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($students as $student)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td class="px-4 py-3">
                                @if ($student->users->isNotEmpty())
                                    @foreach ($student->users as $guardian)
                                        <div>{{ $guardian->name }}</div>
                                    @endforeach
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php $payment = $payments->get($student->id); @endphp
                                @if ($payment)
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                        {{ $payment->status === 'refunded' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($trip->permission_form_id)
                                    @php $studentSignatures = $signatures->get($student->id); @endphp
                                    @if ($studentSignatures && $studentSignatures->isNotEmpty())
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                            Signed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                            Pending
                                        </span>
                                    @endif
                                @else
                                    <span class="text-zinc-400">No form</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

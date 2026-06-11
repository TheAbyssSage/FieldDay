<div class="p-4">
    <div class="mb-4 flex items-center justify-between">
        <flux:heading size="xl">Users</flux:heading>
        <flux:button :href="route('admin.users.create')" variant="primary" wire:navigate>New user</flux:button>
    </div>

    <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 text-neutral-500 dark:border-neutral-700">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ ucfirst($user->role?->name ?? '—') }}</td>
                        <td class="px-4 py-3">
                            <flux:button size="sm" variant="danger" wire:click="delete({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" :disabled="$user->is(auth()->user())">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-neutral-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-layouts::app :title="__('Admin dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <flux:heading size="xl">Admin dashboard</flux:heading>
        <flux:text>Welcome, {{ auth()->user()->name }} — you're logged in as an admin.</flux:text>
    </div>
</x-layouts::app>   
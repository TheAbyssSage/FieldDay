<x-layouts::app :title="__('Teacher dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <flux:heading size="xl">Teacher dashboard</flux:heading>
        <flux:text>Welcome, {{ auth()->user()->name }} — you're logged in as a teacher.</flux:text>
    </div>
</x-layouts::app>   
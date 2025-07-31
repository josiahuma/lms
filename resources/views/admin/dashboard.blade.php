<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4">
            <p class="text-gray-700">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
        </div>
    </div>
</x-app-layout>

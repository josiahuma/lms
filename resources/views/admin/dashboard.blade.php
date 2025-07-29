<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="text-gray-600">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
        </div>
    </div>
</x-app-layout>

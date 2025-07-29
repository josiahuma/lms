<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Students in {{ $course->title }}</h2>
    </x-slot>

    <div class="py-6 px-4">
        @if ($students->isEmpty())
            <p>No students have enrolled yet.</p>
        @else
            <ul class="list-disc ml-6 space-y-2">
                @foreach ($students as $student)
                    <li>
                        {{ $student->name }} ({{ $student->email }})
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="text-blue-600 underline">← Back to Dashboard</a>
        </div>
    </div>
</x-app-layout>

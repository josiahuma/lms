<x-app-layout>
    <x-slot name="header">
        <h2>All Courses</h2>
    </x-slot>

    <div class="py-4 px-4">
        @if (session('success'))
            <p class="text-green-600">{{ session('success') }}</p>
        @endif

        @if(auth()->user()->role === 'instructor')
            <a href="{{ route('courses.create') }}" class="btn btn-primary">+ Create Course</a>
        @endif

        <ul class="mt-4 space-y-2">
            @foreach ($courses as $course)
                <li class="border p-3 rounded">
                    <a href="{{ route('courses.show', $course) }}">
                        <strong>{{ $course->title }}</strong>
                    </a><br>
                    <small>Instructor: {{ $course->instructor->name }}</small><br>
                    <span>£{{ number_format($course->price, 2) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>

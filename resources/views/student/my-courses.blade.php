<x-app-layout>
    <x-slot name="header">
        <h2>My Enrolled Courses</h2>
    </x-slot>

    <div class="py-6 px-4">
        @if ($courses->isEmpty())
            <p>You have not enrolled in any courses yet.</p>
        @else
            <ul class="space-y-4">
                @foreach ($courses as $course)
                    <li class="border p-4 rounded">
                        <a href="{{ route('courses.show', $course) }}" class="text-lg font-semibold">
                            {{ $course->title }}
                        </a><br>
                        <small>Instructor: {{ $course->instructor->name }}</small><br>
                        <span>Price: £{{ number_format($course->price, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>

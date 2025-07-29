<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Instructor Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4">
        <h3 class="text-lg font-semibold mb-4">Your Courses</h3>

        @if ($courses->isEmpty())
            <p>You haven't created any courses yet.</p>
        @else
            <ul class="space-y-4">
                @foreach ($courses as $course)
                    <li class="border p-4 rounded">
                        <h4 class="text-lg font-bold">{{ $course->title }}</h4>
                        <p>{{ $course->description }}</p>
                        <p><strong>Price:</strong> £{{ number_format($course->price, 2) }}</p>

                        <div class="mt-2">
                            <strong>Lessons:</strong>
                            <ul class="list-disc ml-5">
                               @forelse ($course->lessons as $lesson)
                                    <li class="flex justify-between items-center">
                                        <a href="{{ route('lessons.show', $lesson) }}" class="text-blue-600 underline">
                                            {{ $lesson->title }}
                                        </a>
                                        <div class="flex gap-2 items-center">
                                            <a href="{{ route('lessons.edit', $lesson) }}" class="text-blue-600 text-sm underline">✏️ Edit</a>

                                            <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm underline">🗑️ Delete</button>
                                            </form>
                                        </div>
                                    </li>
                                @empty
                                    <li>No lessons available yet.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-2">
                            <a href="{{ route('courses.show', $course) }}" class="text-blue-600 underline">
                                View Course
                            </a>
                        </div>
                        <div class="mt-2 flex gap-4">
                            <p><strong>Enrolled Students:</strong> {{ $course->students->count() }}</p>

                            <a href="{{ route('courses.students', $course) }}" class="text-blue-600 underline">
                                View Enrolled Students
                            </a>
                        </div>
                        <div class="mt-2 flex gap-4">
                            <a href="{{ route('courses.edit', $course) }}" class="text-blue-600 underline">✏️ Edit</a>

                            <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 underline">🗑️ Delete</button>
                            </form>
                        </div>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>

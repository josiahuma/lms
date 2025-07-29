<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $course->title }}</h2>
    </x-slot>

    <div class="py-6 px-4">
        <p><strong>Description:</strong> {{ $course->description }}</p>
        <p><strong>Price:</strong> £{{ number_format($course->price, 2) }}</p>
        <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>

        <hr class="my-4">

        <h3 class="text-lg font-bold mb-2">Lessons</h3>
        <ul class="list-disc ml-5">
            @forelse ($course->lessons as $lesson)
                <li class="flex justify-between items-center">
                    <a href="{{ route('lessons.show', $lesson) }}" class="text-blue-600 underline">
                        {{ $lesson->title }}
                    </a>
                    @if (auth()->user()->role === 'instructor')
                        <div class="flex gap-2 items-center">
                            <a href="{{ route('lessons.edit', $lesson) }}" class="text-blue-600 text-sm underline">✏️ Edit</a>

                            <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm underline">🗑️ Delete</button>
                            </form>
                        </div>
                    @endif
                </li>
            @empty
                <li>No lessons available yet.</li>
            @endforelse
        </ul>

        @if (auth()->user()->role === 'instructor')
            <hr class="my-6">
            <h4 class="text-md font-bold">Add a Lesson</h4>
            <form action="{{ route('lessons.store', $course) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label>Lesson Title</label>
                    <input name="title" class="form-control w-full" required>
                </div>

                <div>
                    <label>Lesson Content</label>
                    <textarea name="content" class="form-control w-full"></textarea>
                </div>

                <button class="btn btn-success">Add Lesson</button>
            </form>
        @endif

        @if (auth()->user()->role === 'student')
            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="mt-6">
                @csrf

                @if (auth()->user()->enrolledCourses->contains($course->id))
                    <p class="text-green-600">✅ You are already enrolled in this course.</p>
                @else
                    <button type="submit" class="btn btn-primary">Enroll in this Course</button>
                @endif
            </form>
        @endif

    </div>
</x-app-layout>

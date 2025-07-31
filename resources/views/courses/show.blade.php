<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 md:flex gap-8">

        <!-- Left Column: Course Details -->
        <div class="md:w-2/3">
            <!-- Title and Instructor -->
            <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
            <p class="text-gray-600 mb-4">By <span class="font-medium">{{ $course->instructor->name }}</span></p>

            <!-- Rating (static for now) -->
            <div class="flex items-center text-yellow-400 mb-4">
                ⭐⭐⭐⭐⭐ <span class="ml-2 text-sm text-gray-600">(5.0)</span>
            </div>

            <!-- Description -->
            <p class="text-gray-800 leading-relaxed mb-6">{{ $course->description }}</p>

            <!-- Lessons -->
            <h2 class="text-xl font-semibold mb-2">Course Content</h2>
            <div class="bg-white shadow rounded divide-y">
                @forelse ($course->lessons as $lesson)
                    <div class="p-4 flex justify-between items-center">
                        <a href="{{ route('lessons.show', $lesson) }}" class="text-indigo-600 font-medium hover:underline">
                            {{ $lesson->title }}
                        </a>

                        @if (auth()->user()->role === 'instructor')
                            <div class="flex gap-3">
                                <a href="{{ route('lessons.edit', $lesson) }}" class="text-sm text-blue-600 hover:underline">✏️ Edit</a>
                                <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">🗑️</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-4 text-gray-500">No lessons available yet.</div>
                @endforelse
            </div>

            <!-- Add Lesson Button -->
            @if (auth()->user()->role === 'instructor')
                <div class="mt-6">
                    <a href="{{ route('lessons.create', $course) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                        ➕ Add New Lesson
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="md:w-1/3 mt-8 md:mt-0">
            <div class="bg-white shadow rounded p-6 sticky top-20">
                <h3 class="text-xl font-semibold mb-4">Course Info</h3>
                <p class="text-gray-700 mb-2"><strong>Price:</strong> £{{ number_format($course->price, 2) }}</p>
                <p class="text-gray-700 mb-6"><strong>Instructor:</strong> {{ $course->instructor->name }}</p>

                @if (auth()->user()->role === 'student')
                    <form action="{{ route('courses.enroll', $course) }}" method="POST">
                        @csrf
                        @if (auth()->user()->enrolledCourses->contains($course->id))
                            <p class="text-green-600 font-medium">✅ You're already enrolled.</p>
                        @else
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                Enroll in this Course
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

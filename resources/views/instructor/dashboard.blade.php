<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Instructor Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-2xl font-semibold mb-6">📚 Your Courses</h3>

            @if ($courses->isEmpty())
                <p class="text-gray-600">You haven't created any courses yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($courses as $course)
                        <div class="bg-white rounded-lg shadow p-4 border">
                            {{-- Thumbnail Placeholder --}}
                            <div class="h-40 bg-gray-200 rounded mb-3 flex items-center justify-center text-gray-400">
                                No Image
                            </div>

                            <h4 class="text-lg font-bold">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($course->description, 100) }}</p>
                            <p class="text-sm text-gray-700"><strong>Price:</strong> £{{ number_format($course->price, 2) }}</p>
                            <p class="text-sm text-gray-700 mb-1"><strong>Enrolled:</strong> {{ $course->students->count() }}</p>

                            {{-- Lessons --}}
                            <div class="mt-3">
                                <h5 class="font-semibold mb-1">Lessons</h5>
                                <ul class="text-sm list-disc ml-5 space-y-1">
                                    @forelse ($course->lessons as $lesson)
                                        <li class="flex justify-between items-center">
                                            <a href="{{ route('lessons.show', $lesson) }}" class="text-blue-600 hover:underline">
                                                {{ $lesson->title }}
                                            </a>
                                            <div class="flex gap-2">
                                                <a href="{{ route('lessons.edit', $lesson) }}" class="text-yellow-600 text-xs">✏️</a>
                                                <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 text-xs">🗑️</button>
                                                </form>
                                            </div>
                                        </li>
                                    @empty
                                        <li>No lessons yet</li>
                                    @endforelse
                                </ul>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 text-sm underline">🔍 View</a>
                                <a href="{{ route('courses.students', $course) }}" class="text-blue-600 text-sm underline">👥 Students</a>
                                <a href="{{ route('courses.edit', $course) }}" class="text-yellow-600 text-sm underline">✏️ Edit</a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-sm underline">🗑️ Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
